<?php

use app\components\rbac\RouteAccessControl;
use app\models\User;
use yii\filters\AccessControl;
use yii\web\NotFoundHttpException;

return [
	'as adminAccess' => [
		'class' => AccessControl::className(),
		'rules' => [
			[
				'allow'   => true,
				'roles'   => [User::ROLE_ADMIN],
			],
		],
		'denyCallback' => function ($null, $action) {
			\Yii::$app->errorHandler->errorAction = 'site/error';
			throw new NotFoundHttpException('The requested page does not exist.');
		}
	],

	'as routeAccess' => RouteAccessControl::className(),
];