<?php

use yii\db\Migration;

/**
 * Class m180405_153243_add_customer_table
 */
class m180405_153243_add_customer_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
    	 $this->createTable('customer', [
            'id' => $this->primaryKey(),
            'name' => $this->string(50)->notNull(),
            'phone' => $this->bigInteger()->notNull(),
            'address' => $this->text(),
            'status' => $this->integer(1)->defaultValue(1),
            'cylinder_count' => $this->integer(2)->defaultValue(0),
            'balance' => $this->decimal(12,2)->defaultValue(0),
            'is_deleted' => $this->integer(1)->defaultValue(0),
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
         $this->dropTable('customer');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m180405_153243_add_customer_table cannot be reverted.\n";

        return false;
    }
    */
}
