<?php

namespace justcoded\yii2\rbac;


class Module extends \yii\base\Module
{

	public $defaultRoute = 'index';

//
//	public $module = 'admin';
//
//	public $layoutPath = '@admin/views/layout/main';
//
//
//	public $urlRules = [
//		'admin/permissions'                      => 'admin/rbac/permissions',
//		'admin/rbac/permissions' => 'admin/permissions'
//	];

	public function init()
	{
		parent::init();
	}

}
