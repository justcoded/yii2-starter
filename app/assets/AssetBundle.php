<?php

namespace app\assets;

class AssetBundle extends \yii\web\AssetBundle
{
	public $sourcePath = '@app/assets';

	public $css = [
		'css/site.css',
	];
	public $js = [
	];
	public $depends = [
		'yii\web\YiiAsset',
		'yii\bootstrap4\BootstrapAsset',
	];
}
