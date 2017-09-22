<?php

namespace justcoded\yii2\settings\components;

/**
 * Interface SettingsInterface
 * @package app\extensions\settings\components
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