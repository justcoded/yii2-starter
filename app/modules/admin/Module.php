<?php

namespace app\modules\admin;

use Yii;

class Module extends \yii\base\Module
{
	const BLOCK_CONTENT_HEADER = 'content-header';
	const BLOCK_CONTENT_TITLE = 'content-title';

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

		// configure module
		Yii::configure($this, require(__DIR__ . '/config/main.php'));

		// reconfigure service container
		Yii::configure(Yii::$container, require(__DIR__ . '/config/container.php'));

		// change error action to match admin styles
		Yii::$app->errorHandler->errorAction = 'admin/dashboard/error';
	}
}
