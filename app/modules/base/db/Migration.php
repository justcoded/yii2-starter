<?php
namespace app\modules\base\db;

class Migration extends \yii\db\Migration
{
	/**
	 * Prepare table options based on DB driver
	 *
	 * @return null|string
	 */
	protected function tableOptions()
	{
		$tableOptions = null;
		if ($this->db->driverName === 'mysql') {
			// http://stackoverflow.com/questions/766809/whats-the-difference-between-utf8-general-ci-and-utf8-unicode-ci.
			$tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
		}

		return $tableOptions;
	}
}