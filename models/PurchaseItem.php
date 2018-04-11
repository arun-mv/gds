<?php

namespace app\models;

use Yii;
use yii\behaviors\BlameableBehavior;
use yii\behaviors\TimestampBehavior;
use yii\db\Expression;
/**
 * This is the model class for table "purchase_item".
 *
 * @property int $id
 * @property int $purchase_id
 * @property string $date
 * @property int $item_id
 * @property string $rate
 * @property string $amount
 * @property int $quantity
 * @property int $created_by
 * @property int $updated_by
 * @property string $created_at
 * @property string $updated_at
 *
 * @property Item $item
 * @property Purchase $purchase
 */
class PurchaseItem extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'purchase_item';
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
            [['purchase_id', 'date', 'item_id', 'rate', 'amount', 'quantity', 'created_by', 'created_at'], 'required'],
            [['purchase_id', 'item_id', 'quantity', 'created_by', 'updated_by'], 'integer'],
            [['date', 'created_at', 'updated_at'], 'safe'],
            [['rate', 'amount'], 'number'],
            [['item_id'], 'exist', 'skipOnError' => true, 'targetClass' => Item::className(), 'targetAttribute' => ['item_id' => 'id']],
            [['purchase_id'], 'exist', 'skipOnError' => true, 'targetClass' => Purchase::className(), 'targetAttribute' => ['purchase_id' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'purchase_id' => Yii::t('app', 'Purchase ID'),
            'date' => Yii::t('app', 'Date'),
            'item_id' => Yii::t('app', 'Item ID'),
            'rate' => Yii::t('app', 'Rate'),
            'amount' => Yii::t('app', 'Amount'),
            'quantity' => Yii::t('app', 'Quantity'),
            'created_by' => Yii::t('app', 'Created By'),
            'updated_by' => Yii::t('app', 'Updated By'),
            'created_at' => Yii::t('app', 'Created At'),
            'updated_at' => Yii::t('app', 'Updated At'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getItem()
    {
        return $this->hasOne(Item::className(), ['id' => 'item_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPurchase()
    {
        return $this->hasOne(Purchase::className(), ['id' => 'purchase_id']);
    }
}
