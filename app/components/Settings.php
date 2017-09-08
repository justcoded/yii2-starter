<?php

namespace app\components;

use Yii;
use yii\base\BootstrapInterface;
use yii\base\Component;
use yii\base\InvalidParamException;

/**
 * Class Settings
 *
 * Improves the yii parameters
 *
 * @package app\components
 */
class Settings extends Component implements BootstrapInterface
{
	protected $values = [];

	/**
	 * TODO: make it works with activeRecord / DB
	 * with config set models, which should be configured
	 * with PHPDoc set property names like $app AppSettings (where AppSettings is model with PHPDoc for params)
	 * update magic get/set to configure model object, getter/setter of some property will be part of the model
	 */

	/**
	 * Runs on Yii app boostrap
	 *
	 * @param \yii\base\Application $app
	 */
	public function bootstrap($app)
	{
		$this->values = $app->params;
	}

	/**
	 * Get setting value
	 *
	 * @param string $key
	 *
	 * @return mixed
	 */
	public function get($key)
	{
		if (isset($this->values[$key])) {
			return $this->values[$key];
		} else {
			throw new InvalidParamException("Setting '$key' doesn't exists");
		}
	}

}
