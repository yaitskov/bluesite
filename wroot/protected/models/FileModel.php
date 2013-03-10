<?php

/**
 * Entity is a file of 3D model for a CNC machine.
 * It can be in STL or GCODE or Blender format.
 * User can convert it to other formats, like it, add to favorite, comment.
 * See a 3D model.
 *
 */
class FileModel extends CActiveRecord
{
	/**
	 * The followings are the available columns in table 'tbl_file_model':
	 * @var integer $id
	 * @var string $oriname      file name on the source client machine
	 * @var string $description  project description like readme
	 * @var string $mime         type of file like text/gcode or text/stl or xml/blender
	 * @var string $status       it can be used for marking deleted projects
	 * @var integer $created     unix time stamp when file was uploaded
	 * @var integer $cursize     size of file in bytes
	 * @var integer $created     unix time stamp when file was uploaded     
	 * @var string $author_id    profile who uploaded this file
	 * @var string $project_id   file is attached to the project
	 */

    const ST_NEW = 'ne';
    const ST_DELETED = 'de';
    
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
		return '{{model_file}}';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('oriname, mime, status, created, cursize, author_id, project_id', 'required'),
			array('status', 'in', 'range'=>array(self::ST_NEW, self::ST_DELETED)),
			array('oriname', 'length', 'max'=>120),
			array('mime', 'length', 'max'=>20),
            array('oriname, mime', 'filter', 'filter' => 'trim'),
			array('description', 'safe'),
		);
	}

	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
		// NOTE: you may need to adjust the relation name and the related
		// class name for the relations automatically generated below.
		return array(
			'author' => array(self::BELONGS_TO, 'User', 'author_id'),
			'project' => array(self::BELONGS_TO, 'Project', 'project_id'),            
			'comments' => array(self::HAS_MANY, 'Comment', 'obj_id',
                'condition'=>'obj_type=:ot and (status = :sta or status = :stb)',
                'params'   => array(':ot' => Comment::OT_FILE,
                    ':sta' => Comment::ST_NEW, ':stb' => Comment::ST_VALID),
                'order'    =>'created ASC'),
            'ncomments' => array(self::STAT, 'Comment', 'obj_id',
                'condition'=>'obj_type = :ot and (status = :sta or status = :stb)',
                'params'   => array(':ot' => Comment::OT_FILE,
                    ':sta' => Comment::ST_NEW, ':stb' => Comment::ST_VALID),
            ),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'Id',
			'oriname' => 'Origin file name',
			'description' => 'Description',
			'status' => 'Status',
			'created' => 'Create Time',
			'author_id' => 'Author',
			'project_id' => 'Project',            
            'mime' => 'Mime',
            'cursize' => 'Size',            
		);
	}

	/**
	 * @return string the URL that shows the detail of the post
	 */
	public function getUrl()
	{
		return Yii::app()->createUrl('fileModel/view', array('id'=>$this->id));
	}

    public static function canCreate($user) {
        return !$user->isGuest;
    }

    public function canComment($user) {
        return !$user->isGuest;
    }
}