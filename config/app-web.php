<?php

\Yii::setAlias('@app', dirname(__DIR__) . '/app');

$params = require __DIR__ . '/params.php';
$db     = require __DIR__ . '/db.php';
$routes  = require __DIR__ . '/routes.php';

dotenv()->required('APP_KEY')->notEmpty();

$config = [
	'id'         => 'main',
	'basePath'   => dirname(__DIR__) . '/app',
	'runtimePath'   => dirname(__DIR__) . '/runtime',
	'vendorPath'   => dirname(__DIR__) . '/vendor',
	'bootstrap'  => ['log'],
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
		'cache'        => [
			'class' => 'yii\caching\FileCache',
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
			'rules' => $routes,
		],
		'formatter' => [
			'class' => 'app\components\i18n\Formatter',
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
	],
	'params'     => $params,
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
