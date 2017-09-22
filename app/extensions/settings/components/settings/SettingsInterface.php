<?php

namespace justcoded\yii2\settings\components\settings;

/**
 * Interface SettingsInterface
 * @package app\extensions\settings\components\settings
 */
interface SettingsInterface
{
	/**
	 * @param $section
	 * @param $key
	 *
	 * @return mixed
	 */
	public function get($section, $key);
	
	/**
	 * @param $section
	 * @param $key
	 * @param $value
	 *
	 * @return mixed
	 */
	public function set($section, $key, $value);
}