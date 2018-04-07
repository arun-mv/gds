<?php

use yii\db\Migration;

/**
 * Class m180405_153719_add_expense_table
 */
class m180405_153719_add_expense_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
	 $this->createTable('expense', [
            'id' => $this->primaryKey(),
            'emp_id' => $this->integer()->notNull(),
            'date' => $this->date()->notNull(),
            'type' => $this->integer(1)->notNull(),
            'amount' => $this->decimal(7,2)->notNull(),
            'remarks' => $this->string(150),
            'created_by' => $this->integer()->notNull(),
            'updated_by' => $this->integer(),
            'created_at' => $this->datetime()->notNull(),
  	    'updated_at' => $this->datetime(),
        ]);
        
        $this->addForeignKey ('fk_expense_employee', 'expense', 'emp_id', 'employee', 'id', 'NO ACTION', 'CASCADE' );
    
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropForeignKey ( 'fk_expense_employee', 'expense');
        $this->dropTable('expense');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m180405_153719_add_expense_table cannot be reverted.\n";

        return false;
    }
    */
}
