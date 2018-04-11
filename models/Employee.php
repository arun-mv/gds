<?php

namespace app\models;

use Yii;
use yii\behaviors\BlameableBehavior;
use yii\behaviors\TimestampBehavior;
use yii\db\Expression;
use yii2tech\ar\softdelete\SoftDeleteBehavior;

/**
 * This is the model class for table "employee".
 *
 * @property int $id
 * @property string $name
 * @property int $phone
 * @property string $designation
 * @property string $address
 * @property int $status
 * @property string $joined_on
 * @property string $resigned_on
 * @property int $created_by
 * @property int $updated_by
 * @property string $created_at
 * @property string $updated_at
 *
 * @property Expense[] $expenses
 */
class Employee extends \yii\db\ActiveRecord
{

	const STATUS_ACTIVE=1;
	const STATUS_INACTIVE=0;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'employee';
    }
    
    
    public function behaviors()
	{
		return [
			BlameableBehavior::className(),
			'TimestampBehavior' => [
				'class' => TimestampBehavior::className(),
				'value' => new Expression('NOW()'),
			],
			'softDeleteBehavior' => [
                'class' => SoftDeleteBehavior::className(),
                'softDeleteAttributeValues' => [
                    'is_deleted' => true
                ],
                'replaceRegularDelete' => true
            ],
		];
	}

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name', 'phone', 'designation', 'joined_on'], 'required','on'=>'create'],
            [['status', 'created_by', 'updated_by'], 'integer'],
            [['phone'], 'number','numberPattern'=>'/^[6-9][0-9]{9}$/','message'=>'Enter 10 digit valid number'],
            [['address'], 'string'],
            [['joined_on', 'resigned_on', 'created_at', 'updated_at'], 'safe'],
            [['name'], 'string', 'max' => 50],
            [['designation'], 'string', 'max' => 30],
        ];
    }
    
    public function getEId()
    {
    	return "E".str_pad($this->id,'2','0',STR_PAD_LEFT);
    }
    
    public static function getStatusList()
    {
    	return [self::STATUS_ACTIVE=>'Active',self::STATUS_INACTIVE=>'Inactive'];
    }
    
    public function getStatusText()
    {
    	if(isset(self::getStatusList()[$this->status]))
    		return self::getStatusList()[$this->status];
    	return '-';
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'name' => Yii::t('app', 'Name'),
            'phone' => Yii::t('app', 'Phone'),
            'designation' => Yii::t('app', 'Designation'),
            'address' => Yii::t('app', 'Address'),
            'status' => Yii::t('app', 'Status'),
            'joined_on' => Yii::t('app', 'Joined On'),
            'resigned_on' => Yii::t('app', 'Resigned On'),
            'created_by' => Yii::t('app', 'Created By'),
            'updated_by' => Yii::t('app', 'Updated By'),
            'created_at' => Yii::t('app', 'Created At'),
            'updated_at' => Yii::t('app', 'Updated At'),
        ];
    }
    
    
    /**
     * @overriding existing find() for soft delete
     */
    public static function find()
    {
        return parent::find()->where(['is_deleted' => false]);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getExpenses()
    {
        return $this->hasMany(Expense::className(), ['emp_id' => 'id']);
    }
}
