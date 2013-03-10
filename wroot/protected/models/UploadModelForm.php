<?php

class UploadModelForm extends CFormModel {
    public $fileModel;
    public $project_id;
    public $description;

    public function rules() {
        return array(
            array('project_id', 'required'),
            array('description', 'length', 'max' => 10000),
            array('project_id', 'exist', 'className' => 'Project', 'attributeName' => 'id'),
            array('fileModel', 'file',
                'types' => 'jpg, jpeg, png', 'maxSize' => Yii::app()->params['max-uploadable-file'])
        );
    }
}