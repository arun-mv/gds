<?php

use yii\db\Migration;

class m130524_201442_init extends Migration
{
    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            // http://stackoverflow.com/questions/766809/whats-the-difference-between-utf8-general-ci-and-utf8-unicode-ci
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable('{{%user}}', [
            'id' => $this->primaryKey(),
            'username' => $this->string(32)->notNull()->unique(),
            'auth_key' => $this->string(32)->notNull(),
            'password_hash' => $this->string(100)->notNull(),
            'password_reset_token' => $this->string()->unique(),
            'email' => $this->string(75)->notNull()->unique(),
		
	        'type' => $this->smallInteger()->notNull(),	
            'status' => $this->smallInteger(),
            'last_login' => $this->datetime(),
            'create_time' => $this->datetime()->notNull(),
            'update_time' => $this->datetime(),
            'is_deleted' => $this->smallInteger()->defaultvalue(0),
        ], $tableOptions);
    }

    public function down()
    {
        $this->dropTable('{{%user}}');
    }
}
