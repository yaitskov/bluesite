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
     * @var integer schema_consistent  1 means all previous updates applies witout problems
     *                                   
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
			array('schema_version, schema_consistent', 'required'),
            array('schema_consistent', 'in', 'range' => array(1, 0)),
            array('schema_version', 'numerical', 'integerOnly' => true),
		);
	}

    public function unconsistent() {
        $this->schema_consistent = 0;
        if (!$this->save()) {
            throw new Exception("failed to mark schema as incosistent");
        }        
    }

    public function consistent($version) {
        $this->schema_consistent = 1;
        if ($version <= $this->schema_version) {
            throw new Exception("new version $version is less than previous");
        }
        $this->schema_version = $version;
        if (!$this->save()) {
            throw new Exception("failed to update schema version upto "
                . $this->schema_version);
        }        
    }

    public function checkConsistency() {
        if ($this->schema_consistent != 1) {
            echo "ERROR: database schema has version " . $this->schema_version
                . " and NOT consistent.\n";
            exit(1);
        }
    }
}