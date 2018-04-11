<?php

use yii\db\Migration;

/**
 * Class m180407_100541_add_table_settings
 */
class m180407_100541_add_table_settings extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
	$this->createTable('settings', [
            'id' => $this->primaryKey(),
            'key' => $this->string(60)->notNull(),
            'value' => $this->text()->notNull(),
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
        $this->dropTable('settings');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m180407_100541_add_table_settings cannot be reverted.\n";

        return false;
    }
    */
}
