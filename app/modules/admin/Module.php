<?php

namespace app\modules\admin;

use Yii;

class Module extends \yii\base\Module
{
	/**
	 * @inheritdoc
	 */
	public $layout = 'main';

	/**
	 * @inheritdoc
	 */
	public $defaultRoute = 'dashboard';

	/**
	 * @inheritdoc
	 */
	public function init()
	{
		parent::init();

		// reconfigure app
		$app_config = require(__DIR__ . '/config/app.php');
		Yii::configure(Yii::$container, $app_config['container']);

		foreach ($app_config['components'] as $component => $options) {
			Yii::configure(Yii::$app->get($component), $options);
		}

		// configure module
		Yii::configure($this, require(__DIR__ . '/config/mod.php'));
	}
}
