<?php

namespace app\modules\admin\controllers;

use yii\filters\AccessControl;
use yii\web\Controller;

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
				'class' => 'justcoded\yii2\settings\actions\SettingsAction',
				'modelClass' => 'justcoded\yii2\settings\forms\AppSettingsForm',
			],
		];
	}
}