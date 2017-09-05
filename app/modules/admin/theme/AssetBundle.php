<?php

namespace app\modules\admin\theme;

class AssetBundle extends \yii\web\AssetBundle
{
	public $sourcePath = '@app/modules/admin/theme';

	public $css = [
		'css/extra.css',
	];
	public $js = [
	];
	public $depends = [
		'dmstr\web\AdminLteAsset',
		//'app\modules\admin\theme\AdminltePluginsAssetBundle',
		'yii\web\YiiAsset',
		'yii\bootstrap\BootstrapAsset',
	];
}
