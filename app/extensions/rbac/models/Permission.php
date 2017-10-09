<?php

namespace justcoded\yii2\rbac\models;


use Yii;
use yii\helpers\ArrayHelper;
use yii\rbac\Permission as RbacPermission;
use yii\rbac\Role as RbacRole;
use yii\rbac\Rule as RbacRule;

class Permission
{
	public static $itemsCache;

	/**
	 * Alias for authManager getPermission
	 *
	 * @param string $name
	 *
	 * @return null|RbacPermission
	 */
	public static function find($name)
	{
		return Yii::$app->authManager->getPermission($name);
	}

	/**
	 * Return key-value pairs of all permission names
	 *
	 * @return array
	 */
	public static function getList()
	{
		$data = Yii::$app->authManager->getPermissions();

		return ArrayHelper::map($data, 'name', 'name');
	}

	/**
	 * Create permission inside application authManager
	 *
	 * @param string    $name
	 * @param string    $descr
	 * @param RbacRule|null $rule
	 * @param Permission[]|RbacRole[]     $parents  assign permission to some parent
	 *
	 * @return \yii\rbac\Permission
	 */
	public static function create($name, $descr, $rule = null, $parents = [])
	{
		$auth = Yii::$app->authManager;

		// create permission
		$p = $auth->createPermission($name);
		$p->description = $descr;
		if ($rule) {
			$p->ruleName = $rule->name;
		}
		$auth->add($p);

		// assign parents
		foreach ($parents as $parent) {
			$auth->addChild($parent, $p);
		}

		return $p;
	}

	/**
	 * Find route wildcard permission (controller/*).
	 * Creates if not exists
	 *
	 * @param string $baseName
	 * @param string $descrPrefix
	 *
	 * @return RbacPermission|null
	 */
	public static function getWildcard($baseName, $descrPrefix = 'Access ')
	{
		if (false === strpos($baseName, '/')) {
			return null;
		}

		$wildcardName = dirname($baseName) . '/*';

		if (! isset(static::$itemsCache[$wildcardName])) {
			$permission = Yii::$app->authManager->getPermission($wildcardName);
			if (! $permission) {
				$permission = static::create($wildcardName, $descrPrefix . $wildcardName);
			}

			static::$itemsCache[$wildcardName] = $permission;
		}
		return static::$itemsCache[$wildcardName];
	}
}