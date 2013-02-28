<?php

/**
 * There is a profile.
 */
class User extends CActiveRecord
{
    public $passwordRepeat;
	/**
	 * The followings are the available columns in table 'tbl_profile':
	 * @var integer $id
     * @var string $pro_type
	 * @var string $username
	 * @var string $password
	 * @var string $email
	 * @var string $description
     * @var string $passwordRepeat only for registration
	 */

    const TRIAL = 'tri';
    
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
		return '{{profile}}';
	}

    protected function beforeSave() {
        if (!parent::beforeSave()) {
            return false;
        }
        if ($this->isNewRecord) {
            $this->password = $this->hashPassword($this->password);
        }
        return true;
    }

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('username, password, email, pro_type', 'required'),
            array('email', 'email'),
            array('email', 'unique', 'attributeName' => 'email', 'className' => 'User'),
            array('username', 'unique', 'attributeName' => 'username', 'className' => 'User'),            
            array('passwordRepeat', 'required', 'on' => 'insert'),
            array('passwordRepeat', 'compare', 'compareAttribute' => 'password',
            'on' => 'insert'),
			array('description', 'length', 'max'=>100000),
            array('username, password, email', 'length', 'max'=>128),
            array('password', 'length', 'min' => 3),
            // specifies profile capabilities. There will be trial, junior etc.
            array('pro_type', 'in', 'range'=>array('tri'))
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
			'myProjects' => array(self::HAS_MANY, 'Project', 'owner_id'),
            'trackingProjects' => array(self::MANY_MANY, 'Project', 'tbl_follower_project(follower_id, project_id)'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'Id',
			'username' => 'Username',
			'password' => 'Password',
			'passwordRepeat' => 'Password confirmation',
			'email' => 'Email',
			'pro_type' => 'Profile Type',
			'description' => 'Description',            
		);
	}

	/**
	 * Checks if the given password is correct.
	 * @param string the password to be validated
	 * @return boolean whether the password is valid
	 */
	public function validatePassword($password)
	{
		return crypt($password,$this->password)===$this->password;
	}

	/**
	 * Generates the password hash.
	 * @param string password
	 * @return string hash
	 */
	public function hashPassword($password)
	{
		return crypt($password, $this->generateSalt());
	}

	/**
	 * Generates a salt that can be used to generate a password hash.
	 *
	 * The {@link http://php.net/manual/en/function.crypt.php PHP `crypt()` built-in function}
	 * requires, for the Blowfish hash algorithm, a salt string in a specific format:
	 *  - "$2a$"
	 *  - a two digit cost parameter
	 *  - "$"
	 *  - 22 characters from the alphabet "./0-9A-Za-z".
	 *
	 * @param int cost parameter for Blowfish hash algorithm
	 * @return string the salt
	 */
	protected function generateSalt($cost=10)
	{
		if(!is_numeric($cost)||$cost<4||$cost>31){
			throw new CException(Yii::t('Cost parameter must be between 4 and 31.'));
		}
		// Get some pseudo-random data from mt_rand().
		$rand='';
		for($i=0;$i<8;++$i)
			$rand.=pack('S',mt_rand(0,0xffff));
		// Add the microtime for a little more entropy.
		$rand.=microtime();
		// Mix the bits cryptographically.
		$rand=sha1($rand,true);
		// Form the prefix that specifies hash algorithm type and cost parameter.
		$salt='$2a$'.str_pad((int)$cost,2,'0',STR_PAD_RIGHT).'$';
		// Append the random salt string in the required base64 format.
		$salt.=strtr(substr(base64_encode($rand),0,22),array('+'=>'.'));
		return $salt;
	}
}