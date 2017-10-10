<?php

namespace justcoded\yii2\rbac\models;


use Yii;
use yii\helpers\ArrayHelper;
use yii\rbac\Permission as RbacPermission;
use yii\rbac\Role as RbacRole;
use yii\rbac\Rule as RbacRule;

class Role extends Item
{
	/**
	 * @var RbacRole Rbac item object.
	 */
	private $item;

	/**
	 * Role constructor.
	 *
	 * @param RbacRole|null $item
	 */
	public function __construct(RbacRole $item = null)
	{
		if ($item) {
			$this->setItem($item);
		}
	}

	/**
	 * @param RbacRole|null $item
	 */
	public function setItem(RbacRole $item = null)
	{
		$this->item = $item;
	}

	/**
	 * @return RbacRole
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
	 * @return null|Role
	 */
	public static function find($name)
	{
		if ($item = Yii::$app->authManager->getRole($name)) {
			return new Role($item);
		}
		return null;
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

	/**
	 * Create role inside application authManager
	 *
	 * @param string    $name
	 * @param string    $descr
	 *
	 * @return \yii\rbac\Role
	 */
	public static function create($name, $descr)
	{
		$auth = Yii::$app->authManager;

		// create permission
		$r = $auth->createRole($name);
		$r->description = $descr;
		$auth->add($r);

		return $r;
	}

	public static function getPermissionsRecursive($roleName)
	{
		$results = [];

		$children = Yii::$app->authManager->getChildren($roleName);
		foreach ($children as $name => $item) {
			if (Role::TYPE_ROLE == $item->type) {
				continue;
			}

			$results[$name] = $name;
			$results += static::getPermissionsRecursive($name);
		}

		return $results;
	}
}