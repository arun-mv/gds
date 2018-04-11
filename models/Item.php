<?php

namespace app\models;

use Yii;
use yii\behaviors\BlameableBehavior;
use yii\behaviors\TimestampBehavior;
use yii\db\Expression;

/**
 * This is the model class for table "item".
 *
 * @property int $id
 * @property string $name
 * @property string $code
 * @property string $hsn_code
 * @property int $type
 * @property int $rate
 * @property string $taxable_amount
 * @property int $sgst
 * @property int $cgst
 * @property int $opening_stock
 * @property int $minimum_stock
 * @property int $status
 * @property int $created_by
 * @property int $updated_by
 * @property string $created_at
 * @property string $updated_at
 *
 * @property PurchaseItem[] $purchaseItems
 * @property SalesItem[] $salesItems
 * @property Stock[] $stocks
 */
class Item extends \yii\db\ActiveRecord
{

	const STATUS_INACTIVE=0;
	const STATUS_ACTIVE=1;
	
	
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'item';
    }
    
    public function behaviors()
	{
		return [
			BlameableBehavior::className(),
			'TimestampBehavior' => [
				'class' => TimestampBehavior::className(),
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
            [['name', 'code', 'rate', 'taxable_amount', 'sgst', 'cgst'], 'required'],
            [['type', 'rate', 'sgst', 'cgst', 'opening_stock', 'minimum_stock', 'status', 'created_by', 'updated_by'], 'integer','message'=>'Invalid !'],
            [['taxable_amount'], 'number'],
            [['created_at', 'updated_at'], 'safe'],
            [['name'], 'string', 'max' => 50],
            [['code', 'hsn_code'], 'string', 'max' => 10],
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
            'code' => Yii::t('app', 'Code'),
            'hsn_code' => Yii::t('app', 'Hsn Code'),
            'type' => Yii::t('app', 'Type'),
            'rate' => Yii::t('app', 'Rate'),
            'taxable_amount' => Yii::t('app', 'Taxable Amount'),
            'sgst' => Yii::t('app', 'Sgst'),
            'cgst' => Yii::t('app', 'Cgst'),
            'opening_stock' => Yii::t('app', 'Opening Stock'),
            'minimum_stock' => Yii::t('app', 'Minimum Stock'),
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
    public function getPurchaseItems()
    {
        return $this->hasMany(PurchaseItem::className(), ['item_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSalesItems()
    {
        return $this->hasMany(SalesItem::className(), ['item_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getStocks()
    {
        return $this->hasMany(Stock::className(), ['item_id' => 'id']);
    }
}
