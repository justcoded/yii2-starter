<?php

namespace app\assets;

use yii\bootstrap4\BootstrapAsset;
use yii\web\YiiAsset;

class FontAwesomeAsset extends \yii\web\AssetBundle
{
	public $sourcePath = '@vendor/almasaeed2010/adminlte/plugins/fontawesome-free/';

	public $css = [
		'css/all.min.css',
	];

	public $js = [
	];

	public $depends = [
	];
}
