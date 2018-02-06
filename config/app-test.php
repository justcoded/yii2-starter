<?php

\Yii::setAlias('@app', dirname(__DIR__) . '/app');

$params = require __DIR__ . '/params.php';
$db     = require __DIR__ . '/db.php';

/**
 * Application configuration shared by all test types
 */
return [
	'class' => 'app\web\Application',

	'id'         => 'main-tests',
	'basePath'   => dirname(__DIR__) . '/app',
	'runtimePath'   => dirname(__DIR__) . '/runtime',
	'vendorPath'   => dirname(__DIR__) . '/vendor',
	'controllerNamespace' => 'app\\web\\controllers',
	'aliases'    => [
		'@config'=> '@app/../config',
		'@bower' => '@vendor/bower-asset',
		'@npm'   => '@vendor/npm-asset',
	],
	'language'   => 'en-US',
	'components' => [
		'db'           => $db,
		'authManager' => [
			'class' => 'justcoded\yii2\rbac\components\DbManager',
		],
		'mailer'       => [
			'useFileTransport' => true,
		],
		'assetManager' => [
			'basePath' => __DIR__ . '/../web/assets',
			'forceCopy' => YII_DEBUG,
		],
		'urlManager'   => [
			'showScriptName' => true,
		],
		'user'         => [
			'identityClass' => 'app\models\User',
			'loginUrl' => ['auth/login'],
		],
		'request'      => [
			'cookieValidationKey'  => 'test',
			'enableCsrfValidation' => false,
		],
		'formatter' => [
			'class' => 'app\i18n\Formatter',
		],
		'cache' => [
			'class' => 'yii\caching\FileCache',
		],
		'errorHandler' => [
			'errorAction' => 'site/error',
		],
	],
	'params'     => $params,
];
