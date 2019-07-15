<?php

namespace app\modules\admin\assets;

use app\assets\FontAwesomeAsset;

class AssetBundle extends \yii\web\AssetBundle
{
	public $sourcePath = '@app/modules/admin/assets';

	public $css = [
		'css/admin.css'
	];

	public $js = [];

	public $depends = [
		AdminLteAsset::class,
		FontAwesomeAsset::class,
	];
}
