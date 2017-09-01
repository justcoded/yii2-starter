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
		'@database' => dirname(__DIR__) . '/database',
	],
	'controllerMap' => [
		'migrate' => [
			'class' => 'app\console\controllers\MigrateController',
			'migrationTable' => '_migrations',
			'migrationPath' => '@database/migrations',
		],
		'fixture' => [
			'class' => 'yii\faker\FixtureController',
			'namespace' => 'database\fixtures',
			'templatePath' => '@database/fixtures/templates',
			'fixtureDataPath' => '@database/fixtures/data',
		],
	],
	'components'          => [
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
		'user'         => [
			'identityClass'   => 'app\models\User',
		],
	],
	'params'              => $params,
	/*
	'controllerMap' => [
		'fixture' => [ // Fixture generation command line.
			'class' => 'yii\faker\FixtureController',
		],
	],
	*/
];

if (YII_ENV_DEV) {
	// configuration adjustments for 'dev' environment
	$config['bootstrap'][]    = 'gii';
	$config['modules']['gii'] = [
		'class' => 'yii\gii\Module',
	];
}

return $config;
