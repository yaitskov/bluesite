<?php

/**
 * Project is a group of files 3d models, place for discussing them,
 * attaching photes and comments.
 */
class Project extends CActiveRecord
{
	/**
	 * The followings are the available columns in table 'tbl_project':
	 * @var integer $id
	 * @var string $prname       project name
	 * @var string $owner_id     profile id created it
	 * @var string $description  project description like readme 
	 * @var string $status       it can be used for marking deleted projects
	 * @var integer $created     for garba collection
     * @var integer $nfollowers  number people who follows this project
	 */

    const NEWPRO = 'ne';
    const DELETED = 'de';
    
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
		return '{{project}}';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('prname, owner_id, status, created', 'required'),
            // ne - new
            // dl - marked deleted
			array('status', 'in', 'range'=>array(self::NEWPRO, self::DELETED)),
			array('prname', 'length', 'max'=>128),
            array('prname', 'filter', 'filter' => 'trim'),
            //            array('nfollowers', 'number', 'min' => 0),
            array('owner_id, nfollowers', 'numerical', 'integerOnly' => true),
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
			'owner' => array(self::BELONGS_TO, 'User', 'owner_id'),
			'comments' => array(self::HAS_MANY, 'Comment', 'obj_id',
                'condition'=>'obj_type=:ot and (status = :sta or status = :stb)',
                'params'   => array(':ot' => Comment::OT_PROJECT,
                    ':sta' => Comment::ST_NEW, ':stb' => Comment::ST_VALID),
                'order'    =>'created ASC'),
            // try with?
            'ncomments' => array(self::STAT, 'Comment', 'obj_id',
                'condition'=>'obj_type = :ot and (status = :sta or status = :stb)',
                'params'   => array(':ot' => Comment::OT_PROJECT,
                    ':sta' => Comment::ST_NEW, ':stb' => Comment::ST_VALID),
            ),
            
            'interestedUsers' => array(self::MANY_MANY, 'User', 'tbl_follower_project(project_id,follower_id)'),
		);
	}

    private $cacheFollow = null;

    public function canFollow($uid) {
        return $this->owner_id != $uid;
    }
    
    public function doesFollow($uid) {
        /* if ($this->cacheFollow !== null) { */
        /*     return $this->cacheFollow; */
        /* } */
        $this->cacheFollow = FollowerProject::model()->exists(
            'follower_id = :uid and project_id = :prj',
            array(':uid' => $uid, ':prj' => $this->id));
        return $this->cacheFollow;
    }    

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'Id',
			'prname' => 'Name',
			'description' => 'Description',
			'status' => 'Status',
			'created' => 'Create Time',
			'owner_id' => 'Owner',
            'nfollowers' => 'Number followers',
		);
	}

	/**
	 * @return string the URL that shows the detail of the post
	 */
	public function getUrl()
	{
		return Yii::app()->createUrl('project/view', array(
			'id'=>$this->id,
			'title'=>$this->prname,
		));
	}

	/**
	 * This is invoked after the record is deleted.
	 */
	protected function afterDelete()
	{
		parent::afterDelete();
		Comment::model()->deleteAll('post_id='.$this->id);
	}

	/**
	 * Retrieves the list of posts based on the current search/filter conditions.
	 * @return CActiveDataProvider the data provider that can return the needed posts.
	 */
	public function search()
	{
		$criteria=new CDbCriteria;

		$criteria->compare('title',$this->title,true);

		$criteria->compare('status',$this->status);

		return new CActiveDataProvider('Post', array(
			'criteria'=>$criteria,
			'sort'=>array(
				'defaultOrder'=>'status, update_time DESC',
			),
		));
	}

    public function canChange($uid) {
        return $this->owner_id === $uid;
    }

    public static function canCreate($user) {
        return !$user->isGuest;
    }

    public function canComment($user) {
        return !$user->isGuest;
    }
}