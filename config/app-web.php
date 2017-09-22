<?php

\Yii::setAlias('@app', dirname(__DIR__) . '/app');

$params = require __DIR__ . '/params.php';
$db     = require __DIR__ . '/db.php';

dotenv()->required('APP_KEY')->notEmpty();

$config = [
	'id'         => 'main',
	'basePath'   => dirname(__DIR__) . '/app',
	'runtimePath'   => dirname(__DIR__) . '/runtime',
	'vendorPath'   => dirname(__DIR__) . '/vendor',
	'bootstrap'  => ['log', 'settings'],
	'aliases'    => [
		'@config'=> '@app/../config',
		'@bower' => '@vendor/bower-asset',
		'@npm'   => '@vendor/npm-asset',
	],
	'modules' => [
		'admin' => 'app\modules\admin\Module',
	],
	'components' => [
		'request'      => [
			// TODO: move generator to console command
			'cookieValidationKey' => env('APP_KEY'),
		],
		'db'           => $db,
		'user'         => [
			'identityClass'   => 'app\models\User',
			'loginUrl' => ['auth/login'],
			'enableAutoLogin' => true,
		],
		'urlManager' => [
			'enablePrettyUrl' => true,
			'showScriptName' => false,
			'rules' => require(__DIR__ . '/routes.php'),
		],
		'formatter' => [
			'class' => 'app\i18n\Formatter',
		],
		'cache' => [
			'class' => 'yii\caching\FileCache',
		],
		/*
		'i18n' => [
			'translations' => [
				'app*' => [
					'class' => 'yii\i18n\PhpMessageSource',
					'basePath' => '@app/i18n',
					'fileMap' => [
						'app' => 'app.php',
					],
				],
			],
		],
		*/
		'assetManager' => [
			'forceCopy' => YII_DEBUG,
		],
		'errorHandler' => [
			'errorAction' => 'site/error',
		],
		'mailer'       => [
			'class'            => 'yii\swiftmailer\Mailer',
			// send all mails to a file by default. You have to set
			// 'useFileTransport' to false and configure a transport
			// for the mailer to send real emails.
			'useFileTransport' => true,
		],
		'log'          => [
			'traceLevel' => YII_DEBUG ? 3 : 0,
			'targets'    => [
				[
					'class'  => 'yii\log\FileTarget',
					'levels' => ['error', 'warning'],
				],
			],
		],
		'settings' => [
			'class'     => 'justcoded\yii2\settings\components\DbSettings',
			'modelsMap' => [
				'app' => 'justcoded\yii2\settings\forms\AppSettingsForm',
			],
		],
	],
	'params' => $params,
];

if (YII_ENV_DEV) {
	// configuration adjustments for 'dev' environment
	$config['bootstrap'][]      = 'debug';
	$config['modules']['debug'] = [
		'class' => 'yii\debug\Module',
		// uncomment the following to add your IP if you are not connecting from localhost.
		//'allowedIPs' => ['127.0.0.1', '::1'],
	];

	$config['bootstrap'][]    = 'gii';
	$config['modules']['gii'] = [
		'class' => 'yii\gii\Module',
		// uncomment the following to add your IP if you are not connecting from localhost.
		//'allowedIPs' => ['127.0.0.1', '::1'],
	];
}

return $config;
