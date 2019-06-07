<?php

\Yii::setAlias('@srcPath', dirname(__DIR__));
\Yii::setAlias('@app', '@srcPath/app');
\Yii::setAlias('@webroot', '@srcPath/public');

$db       = require __DIR__ . '/db.php';
$settings = require __DIR__ . '/settings.php';

$config = [
	'id'                  => 'main-console',
	'basePath'   => '@app',
	'runtimePath'   => '@srcPath/runtime',
	'vendorPath'   => '@srcPath/vendor',
	'controllerNamespace' => 'app\\console',
	'bootstrap'  => ['log', 'settings'],
	'aliases'    => [
		'@config'=> '@srcPath/config',
		'@migrations' => '@srcPath/database/migrations',
		'@fixtures' => '@srcPath/database/fixtures',
		'@app/fixtures' => '@fixtures',
	],
	'controllerMap' => [
		'migrate' => [
			'class' => \app\modules\base\console\MigrateController::class,
			'migrationPath' => [
				'@migrations',
				'@yii/rbac/migrations',
				'@vendor/justcoded/yii2-settings/migrations'
			],
		],
		'serve' => [
			'class'   => \yii\console\controllers\ServeController::class,
			'docroot' => '@webroot',
		],
		'fixture' => [
			'class' => \yii\faker\FixtureController::class,
			'namespace' => 'app\fixtures',
			'templatePath' => '@fixtures/templates',
			'fixtureDataPath' => '@fixtures/data',
		],
		'rbac' => [
			'class' => \justcoded\yii2\rbac\commands\RbacController::class,
		],
	],
	'components'          => [
		'authManager' => [
			'class' => \justcoded\yii2\rbac\components\DbManager::class,
		],
		'cache' => [
			'class' => \yii\caching\FileCache::class,
		],
		'log'   => [
			'targets' => [
				[
					'class'  => \yii\log\FileTarget::class,
					'levels' => ['error', 'warning'],
				],
			],
		],
		'db'    => $db,
		'settings' => $settings,
	],
];

if (YII_ENV_DEV) {
	// configuration adjustments for 'dev' environment.
	$config['bootstrap'][]    = 'gii';
	$config['modules']['gii'] = [
		'class' => 'yii\gii\Module',
	];
}

return $config;
