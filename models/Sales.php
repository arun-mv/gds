<?php

namespace app\models;

use Yii;
use yii\behaviors\BlameableBehavior;
use yii\behaviors\TimestampBehavior;
use yii\db\Expression;

/**
 * This is the model class for table "sales".
 *
 * @property int $id
 * @property int $customer_id
 * @property string $date
 * @property string $amount
 * @property string $sgst
 * @property string $cgst
 * @property string $discount
 * @property int $status
 * @property int $created_by
 * @property int $updated_by
 * @property string $created_at
 * @property string $updated_at
 *
 * @property Customer $customer
 * @property SalesItem[] $salesItems
 * @property Transaction[] $transactions
 */
class Sales extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'sales';
    }
    
    
    
    
    public function behaviors()
	{
		return [
			BlameableBehavior::className(),
			[
				'class' => TimestampBehavior::className(),
				'createdAtAttribute' => 'create_time',
				'updatedAtAttribute' => 'update_time',
				'value' => new Expression('NOW()'),
			],
		];
	}

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['customer_id', 'date', 'amount', 'sgst', 'cgst', 'discount', 'created_by', 'created_at'], 'required'],
            [['customer_id', 'status', 'created_by', 'updated_by'], 'integer'],
            [['date', 'created_at', 'updated_at'], 'safe'],
            [['amount', 'sgst', 'cgst', 'discount'], 'number'],
            [['customer_id'], 'exist', 'skipOnError' => true, 'targetClass' => Customer::className(), 'targetAttribute' => ['customer_id' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'customer_id' => Yii::t('app', 'Customer ID'),
            'date' => Yii::t('app', 'Date'),
            'amount' => Yii::t('app', 'Amount'),
            'sgst' => Yii::t('app', 'Sgst'),
            'cgst' => Yii::t('app', 'Cgst'),
            'discount' => Yii::t('app', 'Discount'),
            'status' => Yii::t('app', 'Status'),
            'created_by' => Yii::t('app', 'Created By'),
            'updated_by' => Yii::t('app', 'Updated By'),
            'created_at' => Yii::t('app', 'Created At'),
            'updated_at' => Yii::t('app', 'Updated At'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCustomer()
    {
        return $this->hasOne(Customer::className(), ['id' => 'customer_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSalesItems()
    {
        return $this->hasMany(SalesItem::className(), ['sales_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTransactions()
    {
        return $this->hasMany(Transaction::className(), ['sales_id' => 'id']);
    }
}
