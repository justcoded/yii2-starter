<?php

\Yii::setAlias('@app', dirname(__DIR__) . '/app');

$db       = require __DIR__ . '/db.php';
$settings = require __DIR__ . '/settings.php';

/**
 * Application configuration shared by all test types
 */
return [
	'class' => 'yii\web\Application',

	'id'         => 'main-tests',
	'basePath'   => dirname(__DIR__) . '/app',
	'runtimePath'   => dirname(__DIR__) . '/runtime',
	'vendorPath'   => dirname(__DIR__) . '/vendor',
	'bootstrap'  => ['settings'],
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
			'basePath' => __DIR__ . '/../public/assets',
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
		'settings' => $settings,

	],
];
