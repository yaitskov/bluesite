<?php

class CommentController extends Controller
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
			array('allow', // allow authenticated users to access all actions
				'users'=>array('@'),
			),
			array('deny',  // deny all users
				'users'=>array('*'),
			),
		);
	}

    public function actionAdd() {
        $comment = new Comment;
        if (isset($_POST['ajax'])) {
            $this->renderJson(array()); // nothing always ok
        }        
        if (isset($_POST['Comment'])) {
            $comment->attributes = $_POST['Comment'];
            $comment->created = time();
            $comment->status = Comment::ST_NEW;
            $comment->author_id = Yii::app()->user->id;
            if ($comment->save()) {
                $this->renderJson(
                    array(
                        'body' => $this->renderPartial('_view', array('data' => $comment), true),
                    ));
            }
            $this->renderJson(array('error' => "validation errors", 'causes' => $comment->errors));
        }
        $this->renderJson(array('error' => "no data"));
    }

	/**
	 * Updates a particular model.
	 * If update is successful, the browser will be redirected to the 'view' page.
	 */
	public function actionUpdate()
	{
		$model=$this->loadModel();
		if(isset($_POST['ajax']) && $_POST['ajax']==='comment-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
		if(isset($_POST['Comment']))
		{
			$model->attributes=$_POST['Comment'];
			if($model->save())
				$this->redirect(array('index'));
		}

		$this->render('update',array(
			'model'=>$model,
		));
	}

	/**
	 * Marks as deleted.
	 */
	public function actionDelete()
	{
        try {
            $comment = $this->loadModel();
            if (!$comment->canDelete(Yii::app()->user)) {
                $this->renderJson(array('error' => 'You do not have enough permissions'));
            }
            $comment->status = Comment::ST_DELETED;
            if ($comment->save()) {
                $this->renderJson(array());
            } else {
                $this->renderJson(
                    array(
                        'error' => 'cannot delete',
                        'causes' => $comment->errors));
            }
        } catch(CHttpException $e) {
            $this->renderJson(array('error' => $e->getMessage()));
        }
	}

	/**
	 * Returns the data model based on the primary key given in the GET variable.
	 * If the data model is not found, an HTTP exception will be raised.
	 */
	public function loadModel()
	{
		if($this->_model===null)
		{
			if(isset($_GET['id']))
				$this->_model=Comment::model()->findbyPk($_GET['id']);
			if($this->_model===null)
				throw new CHttpException(404,'The requested page does not exist.');
		}
		return $this->_model;
	}
}
