<?php

namespace justcoded\yii2\rbac\models;


use Yii;
use yii\helpers\ArrayHelper;
use yii\rbac\Permission as RbacPermission;
use yii\rbac\Role as RbacRole;
use yii\rbac\Rule as RbacRule;

class Role
{

	/**
	 * Alias for authManager getPermission
	 *
	 * @param string $name
	 *
	 * @return null|RbacRole
	 */
	public static function find($name)
	{
		return Yii::$app->authManager->getRole($name);
	}

	/**
	 * Return key-value pairs of all roles names
	 *
	 * @return array
	 */
	public static function getList()
	{
		$data = Yii::$app->authManager->getRoles();

		return ArrayHelper::map($data, 'name', 'name');
	}

}