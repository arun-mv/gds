<?php

namespace app\models;

use Yii;
use yii\behaviors\BlameableBehavior;
use yii\behaviors\TimestampBehavior;
use yii\db\Expression;
use yii2tech\ar\softdelete\SoftDeleteBehavior;

/**
 * This is the model class for table "customer".
 *
 * @property int $id
 * @property string $name
 * @property int $phone
 * @property string $address
 * @property int $status
 * @property int $cylinder_count
 * @property string $balance
 * @property int $created_by
 * @property int $updated_by
 * @property string $created_at
 * @property string $updated_at
 *
 * @property Sales[] $sales
 * @property Transaction[] $transactions
 */
class Customer extends \yii\db\ActiveRecord
{

	const STATUS_ACTIVE=1;
	const STATUS_INACTIVE=0;
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'customer';
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
            [['name', 'phone','address'], 'required','on'=>'create'],
            [['status', 'cylinder_count', 'created_by', 'updated_by'], 'integer','message'=>'Invalid Number'],
            [['phone'], 'number','numberPattern'=>'/^[6-9][0-9]{9}$/','message'=>'Enter 10 digit valid number'],
            [['address'], 'string'],
            [['balance'], 'number'],
            [['created_at', 'updated_at'], 'safe'],
            [['name'], 'string', 'max' => 50],
        ];
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
            'address' => Yii::t('app', 'Address'),
            'status' => Yii::t('app', 'Status'),
            'cylinder_count' => Yii::t('app', 'Cylinder Count'),
            'balance' => Yii::t('app', 'Balance'),
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
    public function getSales()
    {
        return $this->hasMany(Sales::className(), ['customer_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTransactions()
    {
        return $this->hasMany(Transaction::className(), ['customer_id' => 'id']);
    }
}
