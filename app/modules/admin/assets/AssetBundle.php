<?php

namespace app\modules\admin\assets;

use rmrevin\yii\fontawesome\FontAwesome;
use yii\bootstrap4\BootstrapAsset;
use yii\web\YiiAsset;

class AssetBundle extends \yii\web\AssetBundle
{
	public $sourcePath = '@app/modules/admin/assets';

	public $css = [
		'css/admin.css'
	];

	public $js = [];

	public $depends = [
		AdminLteAssetBundle::class,
		FontAwesomeAssetBundle::class,
	];
}
