<?php

use yii\db\Migration;

/**
 * Class m180405_153655_add_sales_table
 */
class m180405_153655_add_sales_tables extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
	$this->createTable('sales', [
            'id' => $this->primaryKey(),
            'customer_id' => $this->integer()->notNUll(),
            'date' => $this->date()->notNull(),
            'amount' => $this->decimal(10,2)->notNull(),
            'sgst' => $this->decimal(10,2)->notNull(),
            'cgst' => $this->decimal(10,2)->notNull(),
            'discount' => $this->decimal(10,2)->notNull(),
            'status' => $this->integer(1)->defaultValue(1),            
            'created_by' => $this->integer()->notNull(),
            'updated_by' => $this->integer(),
            'created_at' => $this->datetime()->notNull(),
  	    'updated_at' => $this->datetime(),
        ]);
        
        $this->createTable('sales_item', [
            'id' => $this->primaryKey(),
            'sales_id' => $this->integer()->notNull(),
            'date' => $this->date()->notNull(),
            'item_id' => $this->integer()->notNull(),
            'rate' => $this->decimal(10,2)->notNull(),
            'sgst' => $this->decimal(10,2)->notNull(),
            'cgst' => $this->decimal(10,2)->notNull(),
            'quantity' => $this->integer()->notNull(), 
            'amount' => $this->decimal(10,2)->notNull(),           
            'created_by' => $this->integer()->notNull(),
            'updated_by' => $this->integer(),
            'created_at' => $this->datetime()->notNull(),
  	    'updated_at' => $this->datetime(),
        ]);
        
        $this->addForeignKey ('fk_sales_customer', 'sales', 'customer_id', 'customer', 'id', 'NO ACTION', 'CASCADE');
        $this->addForeignKey ('fk_sales_item_sales', 'sales_item', 'sales_id', 'sales', 'id', 'NO ACTION', 'CASCADE');
        $this->addForeignKey ('fk_sales_item_item', 'sales_item', 'item_id', 'item', 'id', 'NO ACTION', 'CASCADE');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropForeignKey ( 'fk_sales_item_sales', 'sales_item');
	$this->dropForeignKey ( 'fk_sales_item_item', 'sales_item');
        $this->dropTable('sales_item');
        $this->dropTable('sales');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m180405_153655_add_sales_table cannot be reverted.\n";

        return false;
    }
    */
}
