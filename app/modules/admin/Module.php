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

		// app rbac behavior conflict with controller behavior to check global admin access
		// to launch it later than admin access check we use module behaviors instead
		Yii::$app->detachBehavior('routeAccess');

		// configure module
		Yii::configure($this, require(__DIR__ . '/config/mod.php'));

	}
}