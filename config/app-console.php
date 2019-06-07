<?php

\Yii::setAlias('@app', dirname(__DIR__) . '/app');

$db       = require __DIR__ . '/db.php';
$settings = require __DIR__ . '/settings.php';

$config = [
	'id'                  => 'main-console',
	'basePath'   => dirname(__DIR__) . '/app',
	'runtimePath'   => dirname(__DIR__) . '/runtime',
	'vendorPath'   => dirname(__DIR__) . '/vendor',
	'controllerNamespace' => 'app\\console\\controllers',
	'bootstrap'  => ['log', 'settings'],
	'aliases'    => [
		'@root' => dirname(__DIR__),
		'@config'=> '@root/config',
		'@migrations' => '@root/database/migrations',
		'@fixtures' => '@root/database/fixtures',
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
		'settings' => $settings,
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
