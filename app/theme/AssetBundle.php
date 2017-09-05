<?php

namespace app\theme;

class AssetBundle extends \yii\web\AssetBundle
{
	public $sourcePath = '@app/theme';

	public $css = [
		'css/site.css',
	];
	public $js = [
	];
	public $depends = [
		'yii\web\YiiAsset',
		'yii\bootstrap\BootstrapAsset',
	];
}
