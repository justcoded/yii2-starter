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

		// reset theme options to module theme.
		Yii::$app->view->theme->setBasePath('@app/modules/admin/theme');
		Yii::$app->errorHandler->errorAction = 'admin/dashboard/error';
	}
}