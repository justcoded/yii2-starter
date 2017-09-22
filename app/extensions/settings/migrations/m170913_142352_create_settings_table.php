<?php

use yii\db\Migration;

/**
 * Handles the creation of table `settings`.
 */
class m170913_142352_create_settings_table extends Migration
{
	/**
	 * @inheritdoc
	 */
	public function up()
	{
		$this->createTable('settings', [
			'section_name' => $this->string()->notNull(),
			'key' => $this->string()->notNull(),
			'value' => $this->binary(),
		]);
		$this->addPrimaryKey('settings_pk', 'settings', ['section_name', 'key']);
	}
	
	/**
	 * @inheritdoc
	 */
	public function down()
	{
		$this->dropTable('settings');
	}
}
