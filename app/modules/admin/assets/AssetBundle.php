<?php

namespace app\modules\admin\assets;

class AssetBundle extends \yii\web\AssetBundle
{
	public $sourcePath = '@app/modules/admin/assets';

	public $css = [
		'css/extra.css',
		'css/rbac.css'
	];
	public $js = [
		'js/rbac.js'
	];
	public $depends = [
		'dmstr\web\AdminLteAsset',
		//'app\modules\admin\assets\AdminltePluginsAssetBundle',
		'yii\web\YiiAsset',
		'yii\bootstrap\BootstrapAsset',
	];
}
