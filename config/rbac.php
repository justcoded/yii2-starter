<?php
/**
 * this file is merged together with main "app-web" config
 */

use yii\filters\AccessControl;
use yii\web\NotFoundHttpException;

return [
	'as adminAccess' => [
		// TODO: write own class to simplify this check.
		'class' => AccessControl::className(),
		'rules' => [
			// custom rule to deny access to "/admin/*" if user doesn't have permission 'administer'
			[
				'allow'   => false,
				'controllers' => ['admin/*'],
				'matchCallback' => function ($rule, $action) {
					return ! \Yii::$app->user->can('administer');
				},
				'denyCallback' => function ($null, $action) {
					\Yii::$app->errorHandler->errorAction = 'site/error';
					throw new NotFoundHttpException('The requested page does not exist.');
				}
			],
			// this one is required to make all other site works.
			[
				'allow' => true,
				'controllers' => ['*'],
			],
		],
	],
	// Route based permission filter
	'as routeAccess' => [
		'class' => 'app\filters\RouteAccessControl',
		'allowActions' => [
			'site/*',
			'auth/*',
		],
	],

	// authManager settings
	'components' => [
		'authManager' => [
			'class' => 'app\rbac\DbManager',
			'defaultRoles' => ['Guest'],
		],
	],
];
