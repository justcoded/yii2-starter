<?php

namespace justcoded\yii2\settings\components;

use yii\db\Query;

class DbSettings extends Settings
{
	/**
	 * @var string
	 */
	public $tableName = '{{%settings}}';
	
	/**
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
	 * @inheritdoc
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