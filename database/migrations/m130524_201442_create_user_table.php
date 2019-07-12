<?php

use yii\db\Migration;
use app\traits\migrations\CreateTableOptions;

/**
 * Handles the creation of table `{{%user}}`.
 */
class m130524_201442_create_user_table extends Migration
{
	use CreateTableOptions;

	/**
	 * {@inheritdoc}
	 */
	public function safeUp()
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
		], $this->createTableOptions());
	}

	/**
	 * {@inheritdoc}
	 */
	public function safeDown()
	{
		$this->dropTable('{{%user}}');
	}
}
