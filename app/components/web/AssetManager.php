<?php

namespace app\components\web;

class AssetManager extends \yii\web\AssetManager
{
	public $forceCopy = true;
	public $appendTimestamp = true;

	public $bundles = [
		'yii\bootstrap\BootstrapAsset' => [
			'sourcePath' => null,
			'basePath' => '@webroot',
			'baseUrl' => '@web',
			'css' => [
				'css/bootstrap.css',
			],
		],
		'yii\bootstrap\BootstrapThemeAsset' => [
			'sourcePath' => null,
			'basePath' => '@webroot',
			'baseUrl' => '@web',
			'css' => [
				'css/bootstrap-theme.css',
			],
		],
	];
}