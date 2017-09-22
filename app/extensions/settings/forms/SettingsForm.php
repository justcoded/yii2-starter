<?php

namespace justcoded\yii2\settings\forms;

use justcoded\yii2\settings\components\settings\SettingsInterface;
use yii\base\Model;
use yii\di\Instance;

/**
 * This is the model class for table "settings".
 *
 * @property string $section_name
 * @property string $key
 * @property string $value
 */
abstract class SettingsForm extends Model
{
	public $loaded = false;
	
	/**
	 * @return $this
	 */
	public function loadData()
	{
		foreach ($this->attributes as $name => $value) {
			$this->$name = Instance::of(SettingsInterface::class)->get()->get($this->sectionName(), $name);
		}
		$this->loaded = true;
		return $this;
	}
	
	/**
	 * @return bool
	 */
	public function save()
	{
		$save = true;
		foreach ($this->getAttributes() as $key => $value) {
			$save &= Instance::of(SettingsInterface::class)->get()->set($this->sectionName(), $key, $value);
		}
		return $save;
	}
	
	/**
	 * @return string
	 */
	abstract public function sectionName();
}
