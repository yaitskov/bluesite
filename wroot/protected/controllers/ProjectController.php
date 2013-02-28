<?php

class ProjectController extends Controller
{
	public $layout='column2';

	/**
	 * @var CActiveRecord the currently loaded data model instance.
	 */
	private $_model;

	/**
	 * @return array action filters
	 */
	public function filters()
	{
		return array(
			'accessControl', // perform access control for CRUD operations
		);
	}

	/**
	 * Specifies the access control rules.
	 * This method is used by the 'accessControl' filter.
	 * @return array access control rules
	 */
	public function accessRules()
	{
		return array(
			array('allow',  // allow all users to access 'index' and 'view' actions.
				'actions'=>array('index','view'),
				'users'=>array('*'),
			),
			array('allow', // allow authenticated users to access all actions
                'actions' => array('myProjects', 'create', 'update', 'delete'),
				'users'=>array('@'),
			),
			array('deny',  // deny all users
				'users'=>array('*'),
			),
		);
	}

    /**
     * List of projects of current user
     */
    public function actionMyProjects() {
		$criteria=new CDbCriteria(array(
                'condition' => 'owner_id=' . Yii::app()->user->id
                . ' and status = :st' ,
                'params' => array(':st' => Project::NEWPRO),
                'order'     => 'created DESC'
		));

		$dataProvider=new CActiveDataProvider('Project', array(
			'pagination'=>array(
				'pageSize'=>Yii::app()->params['projectsPerPage'],
			),
			'criteria'=>$criteria,
		));

		$this->render('mylist',array(
			'dataProvider'=>$dataProvider,
		));
    }

	/**
	 * Displays a particular model.
	 */
	public function actionView()
	{
		$project=$this->loadModel();

		$this->render('view',array(
			'model'=>$project,
		));
	}

	/**
	 * Creates a new model.
	 * If creation is successful, the browser will be redirected to the 'view' page.
	 */
	public function actionCreate()
	{
		$model=new Project;
		if(isset($_POST['Project']))
		{
			$model->attributes=$_POST['Project'];
            $model->owner_id = Yii::app()->user->id;
            $model->status   = Project::NEWPRO;
            $model->created  = time();
			if($model->save()) {
				$this->redirect(array('view','id'=>$model->id));
            }
		}

		$this->render('create',array(
			'model'=>$model,
		));
	}

	/**
	 * Updates a particular model.
	 * If update is successful, the browser will be redirected to the 'view' page.
	 */
	public function actionUpdate()
	{
		$model=$this->loadModel();
		if(isset($_POST['Project']))
		{
			$model->attributes=$_POST['Project'];
			if($model->save())
				$this->redirect(array('view','id'=>$model->id));
		}

		$this->render('update',array(
			'model'=>$model,
		));
	}

	/**
	 * Deletes a particular model.
	 * If deletion is successful, the browser will be redirected to the 'index' page.
	 */
	public function actionDelete()
	{
        // we only allow deletion via POST request
        $model = $this->loadModel();
        $model->status = Project::DELETED;
        $model->save();

        // if AJAX request (triggered by deletion via admin grid view), we should not redirect the browser
        if(!isset($_GET['ajax']))
            $this->redirect(array('project/myProjects'));
	}

	/**
	 * Lists all models.
	 */
	public function actionIndex()
	{
		$criteria=new CDbCriteria(array(
			'condition'=>'status='.Post::STATUS_PUBLISHED,
			'order'=>'update_time DESC',
			'with'=>'commentCount',
		));

		$dataProvider=new CActiveDataProvider('Post', array(
			'pagination'=>array(
				'pageSize'=>Yii::app()->params['postsPerPage'],
			),
			'criteria'=>$criteria,
		));

		$this->render('index',array(
			'dataProvider'=>$dataProvider,
		));
	}

	/**
	 * Returns the data model based on the primary key given in the GET variable.
	 * If the data model is not found, an HTTP exception will be raised.
	 */
	public function loadModel()	{
		if($this->_model===null) {
			if(isset($_GET['id'])) {
				$this->_model=Project::model()->findByPk($_GET['id']);
			}
			if($this->_model===null) {
				throw new CHttpException(404,'The requested page does not exist.');
            }
		}
		return $this->_model;
	}
}
