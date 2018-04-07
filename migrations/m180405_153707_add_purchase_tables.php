<?php

use yii\db\Migration;

/**
 * Class m180405_153707_add_purchase_table
 */
class m180405_153707_add_purchase_tables extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
	$this->createTable('purchase', [
            'id' => $this->primaryKey(),
            'date' => $this->date()->notNull(),
            'amount' => $this->decimal(10,2)->notNull(),
            'status' => $this->integer(1)->defaultValue(1),            
            'created_by' => $this->integer()->notNull(),
            'updated_by' => $this->integer(),
            'created_at' => $this->datetime()->notNull(),
  	    'updated_at' => $this->datetime(),
        ]);
        
        $this->createTable('purchase_item', [
            'id' => $this->primaryKey(),
            'purchase_id' => $this->integer()->notNull(),
            'date' => $this->date()->notNull(),
            'item_id' => $this->integer()->notNull(),
            'rate' => $this->decimal(10,2)->notNull(),
            'amount' => $this->decimal(10,2)->notNull(),
            'quantity' => $this->integer()->notNull(),            
            'created_by' => $this->integer()->notNull(),
            'updated_by' => $this->integer(),
            'created_at' => $this->datetime()->notNull(),
  	    'updated_at' => $this->datetime(),
        ]);
        
        $this->addForeignKey ('fk_purchase_item_purchase', 'purchase_item', 'purchase_id', 'purchase', 'id', 'NO ACTION', 'CASCADE');
        $this->addForeignKey ('fk_purchase_item_item', 'purchase_item', 'item_id', 'item', 'id', 'NO ACTION', 'CASCADE'); 
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
	$this->dropForeignKey ( 'fk_purchase_item_item', 'purchase_item');
	$this->dropForeignKey ( 'fk_purchase_item_purchase', 'purchase_item');
        $this->dropTable('purchase_item');
        $this->dropTable('purchase');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m180405_153707_add_purchase_table cannot be reverted.\n";

        return false;
    }
    */
}
