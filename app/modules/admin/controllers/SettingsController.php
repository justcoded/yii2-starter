<?php

namespace app\modules\admin\controllers;

use justcoded\yii2\settings\actions\SettingsAction;
use justcoded\yii2\settings\forms\AppSettingsForm;

/**
 * Class SettingsController
 *
 * @package app\modules\admin\controllers
 */
class SettingsController extends Controller
{
	/**
	* @inheritdoc
	*/
	public function actions()
	{
		return [
			'app' => [
				'class' => SettingsAction::class,
				'modelClass' => AppSettingsForm::class,
			],
		];
	}
}