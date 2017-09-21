<?php

namespace app\extensions\settings\components\settings;

use yii\base\BootstrapInterface;
use yii\base\Component;
use yii\db\Exception;

abstract class Settings extends Component implements SettingsInterface, BootstrapInterface
{
	/**
	 * @var array
	 */
	public $modelsMap;
	
	/**
	 * @var Model[]
	 */
	public $models;
	
	/**
	 * Config example
	 *
	 * 'defaults' => [
	 *      'section_name' => [
	 *          'key' => 'value',
	 *          'key2' => 'value2',
	 *       ]
	 * ]
	 *
	 * @var array
	 */
	public $defaults = [];
	
	/**
	 * @var null|array|false the functions used to serialize and unserialize cached data. Defaults to null, meaning
	 * using the default PHP `serialize()` and `unserialize()` functions.
	 *
	 * If you want to use some more efficient serializer
	 * (e.g. [igbinary](http://pecl.php.net/package/igbinary)),
	 * you may configure this property with a two-element array.
	 * The first element specifies the serialization function, and the second the deserialization
	 * function.
	 *
	 * If this property is set false, data will be directly sent to and retrieved from the underlying
	 * cache component without any serialization or deserialization.
	 */
	public $serializer;
	
	/**
	 * Bootstrap method to be called during application bootstrap stage.
	 * @param Application $app the application currently running
	 */
	public function bootstrap($app)
	{
		$container = \Yii::$container;
		$container->setSingleton(SettingsInterface::class, $this);
	}
	
	/**
	 * @inheritdoc
	 */
	public function init()
	{
		foreach ($this->modelsMap as $key => $params) {
			$this->models[$key] = \Yii::createObject($params);
		}
	}
	
	/**
	 * @param string $name
	 *
	 * @return mixed
	 */
	public function __get($name)
	{
		if (!isset($this->models[$name])) {
			throw new \yii\base\Exception("Settings modelsMap is not configured or key {$name} missing");
		}
		if (!$this->models[$name]->loaded) {
			return $this->models[$name]->loadData();
		}
		return $this->models[$name];
	}
	
	/**
	 * @param $section
	 * @param $key
	 *
	 * @return mixed
	 */
	public function get($section, $key)
	{
		$value = $this->getValue($section, $key);
		if (!$value && isset($this->defaults[$section][$key])) {
			return $this->defaults[$section][$key];
		}
		if ($value === false || $this->serializer === false) {
			return $value;
		} elseif ($this->serializer === null) {
			return unserialize($value);
		} else {
			return call_user_func($this->serializer[1], $value);
		}
	}
	
	/**
	 * @param $section
	 * @param $key
	 * @param $value
	 *
	 * @return bool
	 */
	public function set($section, $key, $value)
	{
		if ($this->serializer === null) {
			$value = serialize($value);
		} elseif ($this->serializer !== false) {
			$value = call_user_func($this->serializer[0], [$value, $dependency]);
		}
		return $this->setValue($section, $key, $value);
	}
	
	/**
	 * Check if there is a value for pk $section-$key
	 *
	 * @param $section
	 * @param $key
	 *
	 * @return bool
	 */
	protected function exists($section, $key)
	{
		return null !== $this->getValue($section, $key);
	}
	
	/**
	 * @param $section
	 * @param $key
	 *
	 * @return mixed
	 */
	abstract protected function getValue($section, $key);
	
	/**
	 * @param $section
	 * @param $key
	 * @param $value
	 *
	 * @return bool
	 */
	abstract protected function setValue($section, $key, $value);
}