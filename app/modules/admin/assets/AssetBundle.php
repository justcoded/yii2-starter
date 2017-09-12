<?php

namespace app\modules\admin\assets;

class AssetBundle extends \yii\web\AssetBundle
{
	public $sourcePath = '@app/modules/admin/assets';

	public $css = [
		'css/extra.css',
	];
	public $js = [
	];
	public $depends = [
		'dmstr\web\AdminLteAsset',
		//'app\modules\admin\assets\AdminltePluginsAssetBundle',
		'yii\web\YiiAsset',
		'yii\bootstrap\BootstrapAsset',
	];
}
