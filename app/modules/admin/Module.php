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

		// configure module.
		Yii::configure($this, require(__DIR__ . '/config/main.php'));

		// reconfigure service container.
		Yii::configure(Yii::$container, require(__DIR__ . '/config/container.php'));

		// set bootstrap version to 4 for Kartik widgets.
		Yii::$app->params['bsVersion'] = 4;
		
		// change error action to match admin styles.
		Yii::$app->errorHandler->errorAction = 'admin/dashboard/error';
	}
}
