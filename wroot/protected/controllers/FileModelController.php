<?php

class FileModelController extends Controller
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
                'actions' => array('upload', 'delete'),
				'users'=>array('@'),
			),
			array('deny',  // deny all users
				'users'=>array('*'),
			),
		);
	}

    /**
     * Renders form for upload and processes submit. 
     */
    public function actionUpload() {
        $upform = new UploadModelForm;
        if (isset($_POST['UploadModelForm'])) { // get chosen file and save it
            $upform->attributes = $_POST['UploadModelForm'];
            $file = CUploadedFile::getInstance($upform, 'fileModel');
            if (!$upform->validate()) {
                $this->render('upload', array('model' => $upform));
            } else {
                $this->persistFileModel($upform, $file);
            }
        } else if (isset($_GET['project_id'])) { // just show empty form to choose a file
            $upform->project_id = $_GET['project_id'];
            $this->render('upload', array('model' => $upform));
        } else {
            throw new CHttpException(400, "project_id is missing");
        }
    }

    protected function persistFileModel($upform, $file) {
        $fileModel = $this->fillFileModel($upform, $file);
        if (!$fileModel->save()) {
            Yii::log('uploaded file model has an error', 'info');            
            $this->render('upload', array('model' => $fileModel));
        }
        try {
            Yii::app()->repo->persistFile($fileModel->project_id, $fileModel->id, $file);
            $this->redirect(array('project/view', 'id' => $fileModel->project_id));
        } catch (Exception $e) {
            Yii::log("$e", 'error', 'app');
            $fileModel->delete();
            $fileModel->addError('system', $e->getMessage());
            $this->render($fileModel, array('model' => $fileModel));
        }
    }

    protected function fillFileModel($upform, $file) {
        $fileModel = new FileModel;
        $fileModel->created = time();
        $fileModel->author_id = Yii::app()->user->id;
        $fileModel->cursize   = $file->size;
        $fileModel->project_id = $upform->project_id;
        $fileModel->mime  = $file->type;
        $fileModel->oriname =  $file->name;
        $fileModel->description = $upform->description;
        $fileModel->status = FileModel::ST_NEW;
        return $fileModel;
    }
}
