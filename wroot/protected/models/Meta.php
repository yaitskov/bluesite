<?php

/**
 * Meta information. Version of database schema etc.
 * This entity exists in 1 instance.
 */
class Meta extends CActiveRecord
{
	/**
	 * The followings are the available columns in table 'tbl_project':
	 * @var integer $id
	 * @var integer $schema_version     
	 */

	/**
	 * Returns the static model of the specified AR class.
	 * @return CActiveRecord the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return '{{meta}}';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('schema_version', 'required'),
            array('schema_version', 'numerical', 'integerOnly' => true),
		);
	}
}