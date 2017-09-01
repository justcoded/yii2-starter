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
	'aliases'    => [
		'@config'=> '@app/../config',
		'@bower' => '@vendor/bower-asset',
		'@npm'   => '@vendor/npm-asset',
	],
	'language'   => 'en-US',
	'components' => [
		'db'           => $db,
		'mailer'       => [
			'useFileTransport' => true,
		],
		'assetManager' => [
			'basePath' => __DIR__ . '/../web/assets',
		],
		'urlManager'   => [
			'showScriptName' => true,
		],
		'user'         => [
			'identityClass' => 'app\models\User',
		],
		'request'      => [
			'cookieValidationKey'  => 'test',
			'enableCsrfValidation' => false,
			// but if you absolutely need it set cookie domain to localhost
			/*
			'csrfCookie' => [
				'domain' => 'localhost',
			],
			*/
		],
	],
	'params'     => $params,
];
