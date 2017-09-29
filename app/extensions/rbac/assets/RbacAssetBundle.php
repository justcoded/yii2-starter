<?php

namespace justcoded\yii2\rbac\assets;

class RbacAssetBundle extends \yii\web\AssetBundle
{
	public $sourcePath = '@app/extensions/rbac/assets';

	public $css = [
		'css/rbac.css',
	];

	public $js = [
		'js/rbac.js'
	];
	public $depends = [
		'app\modules\admin\assets\AssetBundle'
	];
}
