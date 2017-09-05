<?php

namespace app\modules\admin\controllers;

use yii\filters\AccessControl;

class Controller extends \yii\web\Controller
{
	public $layout = 'main';

	/**
	 * @inheritdoc
	 */
	public function behaviors()
	{
		return [
			'access' => [
				'class' => AccessControl::className(),
				'rules' => [
					[
						'allow'   => true,
						'roles'   => ['@'],
					],
				],
			],
		];
	}
}