<?php

namespace justcoded\yii2\rbac\models;


use Yii;
use yii\helpers\ArrayHelper;
use yii\rbac\Permission as RbacPermission;
use yii\rbac\Role as RbacRole;
use yii\rbac\Rule as RbacRule;

class Permission extends Item
{
	/**
	 * @var RbacPermission[]
	 */
	public static $itemsCache;

	/**
	 * @var RbacPermission Rbac item object.
	 */
	private $item;

	/**
	 * Role constructor.
	 *
	 * @param RbacPermission|null $item
	 */
	public function __construct(RbacPermission $item = null)
	{
		if ($item) {
			$this->setItem($item);
		}
	}

	/**
	 * @param RbacPermission|null $item
	 */
	public function setItem(RbacPermission $item = null)
	{
		$this->item = $item;
	}

	/**
	 * @return RbacPermission
	 */
	public function getItem()
	{
		return $this->item;
	}

	/**
	 * Alias for authManager getPermission
	 *
	 * @param string $name
	 *
	 * @return null|Permission
	 */
	public static function find($name)
	{
		if ($item = Yii::$app->authManager->getPermission($name)) {
			return new Permission($item);
		}
		return null;
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

	/**
	 * Permission roles it's assigned to
	 *
	 * @return \yii\rbac\Role[]
	 */
	public function getRoles()
	{
		$parents = [];
		$roles = Yii::$app->authManager->getRoles();
		foreach ($roles as $role) {
			$permissions = Yii::$app->authManager->getPermissionsByRole($role->name);
			if (isset($permissions[$this->item->name])) {
				$role->data['_inherit'] = ! Yii::$app->authManager->hasChild($role, $this->item);
				$parents[$role->name] = $role;
			}
		}

		return $parents;
	}

	/**
	 * Permission direct parent permissions
	 *
	 * @return \yii\rbac\Permission[]
	 */
	public function getParents()
	{
		$parents = [];
		$permissions = Yii::$app->authManager->getPermissions();
		foreach ($permissions as $perm) {
			if (Yii::$app->authManager->hasChild($perm, $this->item)) {
				$parents[$perm->name] = $perm;
			}
		}

		return $parents;
	}

	/**
	 * Permission direct children permissions
	 *
	 * @return \yii\rbac\Permission[]|\yii\rbac\Item[]
	 */
	public function getChildren()
	{
		return Yii::$app->authManager->getChildren($this->item->name);
	}

	/**
	 * Build map of parents and childs
	 *
	 * @param array $names
	 *
	 * @return array
	 */
	public static function getParentChildMap($names = array())
	{
		if (empty($names)) {
			$names = array_keys(Yii::$app->authManager->getPermissions());
		}

		$parents = [];
		$childs = [];

		foreach ($names as $parentName) {
			$children = Yii::$app->authManager->getChildren($parentName);
			foreach ($children as $childName => $item) {
				$childs[$parentName][$childName] = $childName;
				$parents[$childName][$parentName] = $parentName;
			}
		}

		return [$parents, $childs];
	}

}