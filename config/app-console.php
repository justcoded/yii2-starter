<?php

\Yii::setAlias('@app', dirname(__DIR__) . '/app');

$params = require __DIR__ . '/params.php';
$db     = require __DIR__ . '/db.php';
$routes  = require __DIR__ . '/routes.php';

$config = [
	'id'                  => 'main-console',
	'basePath'   => dirname(__DIR__) . '/app',
	'runtimePath'   => dirname(__DIR__) . '/runtime',
	'vendorPath'   => dirname(__DIR__) . '/vendor',
	'bootstrap'  => ['log'],
	'controllerNamespace' => 'app\console\controllers',
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
			],
		],
		'fixture' => [
			'class' => 'yii\faker\FixtureController',
			'namespace' => 'app\fixtures',
			'templatePath' => '@fixtures/templates',
			'fixtureDataPath' => '@fixtures/data',
		],
	],
	'components'          => [
		'authManager' => [
			'class' => 'app\components\rbac\DbManager',
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
	],
	'params'              => $params,
];

if (YII_ENV_DEV) {
	// configuration adjustments for 'dev' environment
	$config['bootstrap'][]    = 'gii';
	$config['modules']['gii'] = [
		'class' => 'yii\gii\Module',
	];
}

return $config;
