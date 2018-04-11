<?php
namespace app\models;

use Yii;
use yii\base\NotSupportedException;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;
use yii\web\IdentityInterface;
use yii2tech\ar\softdelete\SoftDeleteBehavior;
use yii\behaviors\BlameableBehavior;
use yii\db\Expression;

/**
 * User model
 *
 * @property integer $id
 * @property string $username
 * @property string $password_hash
 * @property string $password_reset_token
 * @property string $email
 * @property string $auth_key
 * @property integer $status
 * @property datetime $create_time
 * @property datetime $update_time
 * @property string $password write-only password
 */
class User extends ActiveRecord implements IdentityInterface
{
    const STATUS_DELETED = 0;
    const STATUS_ACTIVE = 1;
    
    const USER_TYPE_ADMIN = 99;
	
    const SCENARIO_INIT = 'init';
    const SCENARIO_CHANGE_PASSWORD = 'change_password';
    const SCENARIO_CHANGE_PASSWORD_ADMIN = 'change_password_admin';
    
    public $new_password;
    public $old_password;
    public $password_repeat;

    
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%user}}';
    }

    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'timeStampBehaviour' => [
            	'class' => TimestampBehavior::className(),
            	'createdAtAttribute' => 'create_time',
	        'updatedAtAttribute' => 'update_time',
	        'value' => new Expression('NOW()'),
            	],
            'softDeleteBehavior' => [
                'class' => SoftDeleteBehavior::className(),
                'softDeleteAttributeValues' => [
                    'is_deleted' => true
                ],
            ],
        ];
    }
    
    /**
     * the attribute mentioned in each scenarios are required
     */
    public function scenarios()
    {
        return [
        	self::SCENARIO_INIT => [],
            self::SCENARIO_CHANGE_PASSWORD => ['old_password', 'new_password','password_reapeat'],
            self::SCENARIO_CHANGE_PASSWORD_ADMIN => ['new_password','password_repeat'],
        ];
    }
    
    
    public function beforeSave($insert)
	{
		if (parent::beforeSave($insert)) {
		    if($this->scenario == self::SCENARIO_CHANGE_PASSWORD_ADMIN){
		    	$this->password_hash = Yii::$app->security->generatePasswordHash($this->new_password);
		    }
		    return true;
		} else {
		    return false;
		}
	}
    
    
    public function getDefaultPassword()
    {
    	if(!empty($this->username))
    		return substr(md5($this->username),0,6);
    	return '';
    }
    
    /**
     * @ overiding find for softDeletion
     */
    public static function find()
    {
        return parent::find()->where(['is_deleted' => false]);
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            ['status', 'default', 'value' => self::STATUS_ACTIVE],
            ['status', 'in', 'range' => [self::STATUS_ACTIVE, self::STATUS_DELETED]],
            
            ['type','required'],
            ['type','in','range'=>[self::USER_TYPE_ADMIN],'message' => 'Unknown UserType'],
            
            ['old_password','validateOldPassword','on' => self::SCENARIO_CHANGE_PASSWORD,'skipOnEmpty' => true],
            ['new_password','validateNewPassword','skipOnEmpty' => true],
            
            ['is_deleted', 'default', 'value' => 0],
        ];
    }
    
    public function validateOldPassword($attribute, $params, $validator)
    {
    	$hash = Yii::$app->security->generatePasswordHash($attribute);
        if ($hash != $this->password_hash) {
            $this->addError($attribute, 'Incorrect Password!');
        }
    }
    
    public function validateNewPassword($attribute, $params, $validator)
    {
        if ($this->$attribute != $this->password_repeat) {
            $this->addError($attribute, 'Passwords do not match');
        }
    }
    
    public function getName()
    {
    	$user = Yii::$app->user->identity;
    	if($user->type == self::USER_TYPE_ADMIN){
    		return 'Admin';
    	}
    	else{
    		return $user->username;
    	}
    }
    
    public function getFullName()
    {
    	$user = $this; 
    	if($user->type == self::USER_TYPE_ADMIN){
    		return 'Admin';
    	}
    	else{
    		return $user->username;
    	}
    }  
        
    public function getTypeName()
    {
    	$typeList = self::getTypeList();
    	if(isset($typeList[$this->type]))
    		return $typeList[$this->type];
    	return 'Unknown';
    }
    
    public static function getTypeList()
    {
    	return [self::USER_TYPE_ADMIN => 'Admin'];
    }

    /**
     * @inheritdoc
     */
    public static function findIdentity($id)
    {
        return static::findOne(['id' => $id, 'status' => self::STATUS_ACTIVE]);
    }

    /**
     * @inheritdoc
     */
    public static function findIdentityByAccessToken($token, $type = null)
    {
        throw new NotSupportedException('"findIdentityByAccessToken" is not implemented.');
    }

    /**
     * Finds user by username
     *
     * @param string $username
     * @return static|null
     */
    public static function findByUsername($username)
    {
    	// By username
        $user =  static::findOne(['username' => $username, 'status' => self::STATUS_ACTIVE]);
        
        // By emailId
        if(empty($user))
        	$user = static::findOne(['email' => $username, 'status' => self::STATUS_ACTIVE]);
        return $user;
    }

    /**
     * Finds user by password reset token
     *
     * @param string $token password reset token
     * @return static|null
     */
    public static function findByPasswordResetToken($token)
    {
        if (!static::isPasswordResetTokenValid($token)) {
            return null;
        }

        return static::findOne([
            'password_reset_token' => $token,
            'status' => self::STATUS_ACTIVE,
        ]);
    }

    /**
     * Finds out if password reset token is valid
     *
     * @param string $token password reset token
     * @return bool
     */
    public static function isPasswordResetTokenValid($token)
    {
        if (empty($token)) {
            return false;
        }

        $timestamp = (int) substr($token, strrpos($token, '_') + 1);
        $expire = Yii::$app->params['user.passwordResetTokenExpire'];
        return $timestamp + $expire >= time();
    }

    /**
     * @inheritdoc
     */
    public function getId()
    {
        return $this->getPrimaryKey();
    }

    /**
     * @inheritdoc
     */
    public function getAuthKey()
    {
        return $this->auth_key;
    }

    /**
     * @inheritdoc
     */
    public function validateAuthKey($authKey)
    {
        return $this->getAuthKey() === $authKey;
    }

    /**
     * Validates password
     *
     * @param string $password password to validate
     * @return bool if password provided is valid for current user
     */
    public function validatePassword($password)
    {
        return Yii::$app->security->validatePassword($password, $this->password_hash);
    }

    /**
     * Generates password hash from password and sets it to the model
     *
     * @param string $password
     */
    public function setPassword($password)
    {
        $this->password_hash = Yii::$app->security->generatePasswordHash($password);
    }

    /**
     * Generates "remember me" authentication key
     */
    public function generateAuthKey()
    {
        $this->auth_key = Yii::$app->security->generateRandomString();
    }

    /**
     * Generates new password reset token
     */
    public function generatePasswordResetToken()
    {
        $this->password_reset_token = Yii::$app->security->generateRandomString() . '_' . time();
    }

    /**
     * Removes password reset token
     */
    public function removePasswordResetToken()
    {
        $this->password_reset_token = null;
    }
}
