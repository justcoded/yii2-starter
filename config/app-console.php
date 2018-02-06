<?php

\Yii::setAlias('@app', dirname(__DIR__) . '/app');

$db     = require __DIR__ . '/db.php';
$params = require __DIR__ . '/params.php';

$config = [
	'id'                  => 'main-console',
	'basePath'   => dirname(__DIR__) . '/app',
	'runtimePath'   => dirname(__DIR__) . '/runtime',
	'vendorPath'   => dirname(__DIR__) . '/vendor',
	'controllerNamespace' => 'app\\console\\controllers',
	'bootstrap'  => ['log', 'settings'],
	'aliases'    => [
		'@config'=> dirname(__DIR__) . '/config',
		'@migrations' => dirname(__DIR__) . '/database/migrations',
		'@fixtures' => dirname(__DIR__) . '/database/fixtures',
		'@app/fixtures' => '@fixtures',
	],
	'controllerMap' => [
		'migrate' => [
			'class' => 'app\console\controllers\MigrateController',
			'migrationPath' => [
				'@migrations',
				'@yii/rbac/migrations',
				'@vendor/justcoded/yii2-settings/migrations'
			],
		],
		'fixture' => [
			'class' => 'yii\faker\FixtureController',
			'namespace' => 'app\fixtures',
			'templatePath' => '@fixtures/templates',
			'fixtureDataPath' => '@fixtures/data',
		],
		'rbac' => [
			'class' => 'justcoded\yii2\rbac\commands\RbacController',
		],
	],
	'components'          => [
		'authManager' => [
			'class' => 'justcoded\yii2\rbac\components\DbManager',
		],
		'cache' => [
			'class' => 'yii\caching\FileCache',
		],
		'log'   => [
			'targets' => [
				[
					'class'  => 'yii\log\FileTarget',
					'levels' => ['error', 'warning'],
				],
			],
		],
		'db'    => $db,
		'settings' => [
			'class'     => 'app\components\Settings',
			'defaults' => $params,
			'modelsMap' => [
				'app' => 'justcoded\yii2\settings\forms\AppSettingsForm',
			],
		],
	],
];

if (YII_ENV_DEV) {
	// configuration adjustments for 'dev' environment
	$config['bootstrap'][]    = 'gii';
	$config['modules']['gii'] = [
		'class' => 'yii\gii\Module',
	];
}

return $config;
