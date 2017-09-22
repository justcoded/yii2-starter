<?php

namespace justcoded\yii2\settings\forms;

use justcoded\yii2\settings\components\SettingsInterface;
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
	/**
	 * This property is used to check if the properties values are loaded
	 *
	 * @var bool
	 */
	protected $loaded = false;
	
	/**
	 * Getter for $loaded property
	 *
	 * @return bool
	 */
	public function getLoaded()
	{
		return $this->loaded;
	}
	
	/**
	 * Method to load settings properties from storage to model
	 *
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
	 * Method to save settings to configured storage
	 *
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
	 * Method to get sectionName, according to which settings value will be stored
	 *
	 * @return string
	 */
	abstract public function sectionName();
}
