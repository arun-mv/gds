<?php

use yii\db\Migration;

/**
 * Class m180405_153644_add_stock_table
 */
class m180405_153644_add_stock_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
	 $this->createTable('stock', [
            'id' => $this->primaryKey(),
            'item_id' => $this->integer()->notNull(),
            'empty' => $this->integer()->notNull(),
            'filled' => $this->integer()->notNull(),
            'date' => $this->date()->notNull(),
            'created_by' => $this->integer()->notNull(),
            'updated_by' => $this->integer(),
            'created_at' => $this->datetime()->notNull(),
  	    'updated_at' => $this->datetime(),
        ]);
        
        $this->addForeignKey ('fk_stock_item', 'stock', 'item_id', 'item', 'id', 'NO ACTION', 'CASCADE' );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
    	$this->dropForeignKey ( 'fk_stock_item', 'stock');
        $this->dropTable('stock');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m180405_153644_add_stock_table cannot be reverted.\n";

        return false;
    }
    */
}
