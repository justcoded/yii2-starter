<?php

namespace justcoded\yii2\settings\components;

use yii\db\Query;

/**
 * Class DbSettings
 * Allows to store settings data in a db
 *
 * @package justcoded\yii2\settings\components
 */
class DbSettings extends Settings
{
	/**
	 * Table name, where settings are stored.
	 * Name is defined according to the extension migration
	 *
	 * @var string
	 */
	public $tableName = '{{%settings}}';
	
	/**
	 * Insert new settings data to a db
	 *
	 * @param $key
	 * @param $value
	 *
	 * @return bool
	 */
	protected function insertValue($section, $key, $value)
	{
		try {
			$db = \Yii::$app->db;
			$db->createCommand()
				->insert($this->tableName, [
					'section_name' => $section,
					'key'     => $key,
					'value'   => [$value, \PDO::PARAM_LOB],
				])->execute();
			return true;
		} catch (\Exception $e) {
			return false;
		}
	}
	
	/**
	 * Update existed in a db settings data
	 *
	 * @param $key
	 * @param $value
	 *
	 * @return bool
	 */
	protected function updateValue($section, $key, $value)
	{
		try {
			$db = \Yii::$app->db;
			$db->createCommand()
				->update($this->tableName,  [
					'value'   => [$value, \PDO::PARAM_LOB],
				], [
					'section_name' => $section,
					'key'     => $key
				])
				->execute();
			return true;
		} catch (\Exception $e) {
			return false;
		}
	}
	
	/**
	 * Get value from a db. if not exists, return false
	 *
	 * @param $section
	 * @param $key
	 *
	 * @return bool
	 */
	protected function getValue($section, $key)
	{
		$query = new Query();
		$value = $query->select(['value'])
			->from($this->tableName)
			->where(['section_name' => $section, 'key' => $key])
			->one();
		return isset($value['value']) ? $value['value'] : false;
	}
	
	/**
	 * Insert value to a db if it's not exist. Otherwise - update existed
	 *
	 * @param $section
	 * @param $key
	 * @param $value
	 *
	 * @return bool
	 */
	protected function setValue($section, $key, $value)
	{
		if ($this->exists($section, $key)) {
			return $this->updateValue($section, $key, $value);
		}
		return $this->insertValue($section, $key, $value);
	}
}