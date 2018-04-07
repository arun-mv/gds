<?php

use yii\db\Migration;

/**
 * Class m180407_073854_add_table_customer_transaction
 */
class m180407_073854_add_table_customer_transaction extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
	$this->createTable('transaction', [
            'id' => $this->primaryKey(),
            'customer_id' => $this->integer()->notNUll(),
            'sales_id' => $this->integer()->notNUll(),
            'date' => $this->date()->notNull(),
            'cash_received' => $this->decimal(10,2)->notNull(),
            'status' => $this->integer(1)->defaultValue(1),            
            'created_by' => $this->integer()->notNull(),
            'updated_by' => $this->integer(),
            'created_at' => $this->datetime()->notNull(),
  	    'updated_at' => $this->datetime(),
        ]);
        
        $this->addForeignKey ('fk_transaction_customer', 'transaction', 'customer_id', 'customer', 'id', 'NO ACTION', 'CASCADE');
        $this->addForeignKey ('fk_transaction_sales', 'transaction', 'sales_id', 'sales', 'id', 'NO ACTION', 'CASCADE');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropForeignKey ( 'fk_transaction_sales', 'transaction');
        $this->dropForeignKey ( 'fk_transaction_customer', 'transaction');
        $this->dropTable('transaction');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m180407_073854_add_table_customer_transaction cannot be reverted.\n";

        return false;
    }
    */
}
