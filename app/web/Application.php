<?php

namespace app\web;

use app\base\ApplicationParams;

/**
 * Custom App class to allow custom components IDE
 *
 * @property \app\components\i18n\Formatter $formatter The main formatter for app
 */
class Application extends \yii\web\Application
{
	/**
	 * @var string the namespace that controller classes are located in.
	 * This namespace will be used to load controller classes by prepending it to the controller class name.
	 * The default namespace is `app\controllers`.
	 *
	 * Please refer to the [guide about class autoloading](guide:concept-autoloading.md) for more details.
	 */
	public $controllerNamespace = 'app\\web\\controllers';

	/**
	 * @var ApplicationParams
	 */
	public $params;

	public function preInit(&$config)
	{
		parent::preInit($config);

		if (isset($config['params'])) {
			$config['params'] = \Yii::createObject(array_merge(
				['class' => ApplicationParams::class],
				$config['params']
			));
		}
	}
}
