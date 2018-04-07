<?php

use yii\db\Migration;

/**
 * Class m180405_153500_add_item_table
 */
class m180405_153500_add_item_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
	$this->createTable('item', [
            'id' => $this->primaryKey(),
            'name' => $this->string(50)->notNull(),
            'code' => $this->string(10)->notNull(),
            'hsn_code' => $this->string(10),
            'type' => $this->integer(1)->notNull(),
            'rate' => $this->integer(5)->notNull(),
            'taxable_amount' => $this->decimal(7,2)->notNull(),
            'sgst' => $this->integer(2)->notNull(),
            'cgst' => $this->integer(2)->notNull(),
            'opening_stock' => $this->integer(5)->defaultValue(0),
            'minimum_stock' => $this->integer(5)->defaultValue(0),
            'status' => $this->integer(1)->defaultValue(1),
            'created_by' => $this->integer()->notNull(),
            'updated_by' => $this->integer(),
            'created_at' => $this->datetime()->notNull(),
  	    'updated_at' => $this->datetime(),
        ]);	
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('item');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m180405_153500_add_item_table cannot be reverted.\n";

        return false;
    }
    */
}
