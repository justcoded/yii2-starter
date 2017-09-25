<?php

class m130524_201442_create_user_table extends \app\console\Migration
{
	public function up()
	{
		$this->createTable('{{%user}}', [
			'id'                   => $this->primaryKey(),
			'username'             => $this->string()->notNull()->unique(),
			'auth_key'             => $this->string(32)->notNull(),
			'password_hash'        => $this->string()->notNull(),
			'password_reset_token' => $this->string()->unique(),
			'email'                => $this->string()->notNull()->unique(),
			'first_name'           => $this->string(64),
			'last_name'            => $this->string(64),

			'status'     => $this->smallInteger()->notNull()->defaultValue(10),
			'created_at' => $this->integer(),
			'updated_at' => $this->integer(),
		], $this->tableOptions());
	}

	public function down()
	{
		$this->dropTable('{{%user}}');
	}
}
