<?php

namespace app\modules\admin\assets;

use yii\bootstrap4\BootstrapAsset;
use yii\bootstrap4\BootstrapPluginAsset;
use yii\jui\JuiAsset;
use yii\web\JqueryAsset;
use yii\web\YiiAsset;

class AdminLteAsset extends \yii\web\AssetBundle
{
	public $sourcePath = '@vendor/almasaeed2010/adminlte/dist/';

	public $css = [
		'css/adminlte.css',
	];

	public $js = [
		'js/adminlte.js',
	];

	public $depends = [
		YiiAsset::class,
		JqueryAsset::class,
		JuiAsset::class,
		BootstrapAsset::class,
		BootstrapPluginAsset::class,
	];
}
