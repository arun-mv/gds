<?php

use yii\db\Migration;

/**
 * Class m180405_153538_add_employee_table
 */
class m180405_153538_add_employee_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
	$this->createTable('employee', [
            'id' => $this->primaryKey(),
            'name' => $this->string(50)->notNull(),
            'phone' => $this->integer(10)->notNull(),
            'designation' => $this->string(30)->notNull(),
            'address' => $this->text(),
            'status' => $this->integer(1)->defaultValue(1),
            'joined_on' => $this->datetime()->notNull(),
            'resigned_on' => $this->datetime(),
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
        $this->dropTable('employee');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m180405_153538_add_employee_table cannot be reverted.\n";

        return false;
    }
    */
}
