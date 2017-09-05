<?php

namespace app\modules\admin\theme;

use yii\web\AssetBundle;

class AdminltePluginsAssetBundle extends AssetBundle
{
	public $sourcePath = '@vendor/almasaeed2010/adminlte/plugins';

	public $js = [
		'datatables/dataTables.bootstrap.min.js',
		// more plugin Js here
	];
	public $css = [
		'datatables/dataTables.bootstrap.css',
		// more plugin CSS here
	];
	public $depends = [
		'dmstr\web\AdminLteAsset',
	];
}