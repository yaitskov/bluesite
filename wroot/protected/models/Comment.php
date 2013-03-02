<?php

/**
 *  Entity comment either project or a separate file.
 */
class Comment extends CActiveRecord
{
	/**
	 * The followings are the available columns in table 'tbl_comment':
	 * @var integer $id
	 * @var string  $content        text of comment
	 * @var string  $status         'ne' new, 'de' deleted by author, 'ok' validated by moderator
	 * @var string  $obj_type       which object is commented 'pr' project or 'fl' file          
	 * @var integer $created        unix time stamp
	 * @var integer $author_id      profile id
	 * @var integer $obj_id         commented object id (project or file)
	 */
    // possible comment statuses
	const ST_NEW = 'ne';     // not checked yet by moderator
	const ST_DELETED = 'de'; // deleted by the author
    const ST_VALID = 'ok' ;  // passed moderator validation
    const ST_BANNED = 'bn';  // moderator treated it as violating site rules.
    const ST_CHANGED = 'ch'; // comment was changed after moderator review

    // possible object types
    const OT_PROJECT = 'pr';
    const OT_FILE    = 'fl';


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
		return '{{comment}}';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('content, author_id, created, obj_type, obj_id, status', 'required'),
			array('status', 'in', 'range'=> array(self::ST_NEW,self::ST_DELETED,
                    self::ST_VALID, self::ST_BANNED, self::ST_CHANGED)),
			array('obj_type','in', 'range' => array(self::OT_FILE, self::OT_PROJECT))
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
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'Id',
			'content' => 'Comment',
			'status' => 'Status',
			'created' => 'Create Time',
			'author_id' => 'Name',
			'obj_type' => 'Type',
		);
	}

    public function canDelete($user) {
        return $this->author_id == $user->id;
    }
}