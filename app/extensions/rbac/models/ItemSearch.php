<?php

namespace justcoded\yii2\rbac\models;

use yii\base\Model;
use yii\data\ArrayDataProvider;
use yii\rbac\Role as RbacRole;
use yii\rbac\Permission as RbacPermission;
use Yii;


class ItemSearch extends Model
{
	public $roleName;
	public $permName;
	public $permRole;

	/**
	 * @inheritdoc
	 */
	public function rules()
	{
		$roles = array_keys(Yii::$app->authManager->getRoles());
		return [
			[['roleName', 'permName'], 'string'],
			[['permRole'], 'in', 'range' => $roles],
		];
	}

	/**
	 * @param $params
	 *
	 * @return ArrayDataProvider
	 */
	public function searchRoles($params)
	{
		$this->load($params);

		$roles = Yii::$app->authManager->getRoles();
		if ($this->roleName) {
			$roles = array_filter($roles, function (RbacRole $role) {
				return false !== strpos(strtolower($role->name), $this->roleName);
			});
		}

		$dataProvider = new ArrayDataProvider([
			'allModels'  => $roles,
			'pagination' => [
				'pageSize' => 20,
			],
		]);

		return $dataProvider;
	}

	/**
	 * @param $params
	 *
	 * @return ArrayDataProvider
	 */
	public function searchPermissions($params)
	{
		$this->load($params);

		$permissions = $this->permRole ? Yii::$app->authManager->getPermissionsByRole($this->permRole) :
			Yii::$app->authManager->getPermissions();

		if ($this->permName) {
			$permissions = array_filter($permissions, function (RbacPermission $permission) {
				return false !== strpos(strtolower($permission->name), $this->permName);
			});
		}

		$dataProvider = new ArrayDataProvider([
			'allModels'  => $permissions,
			'pagination' => [
				'pageSize' => 20,
			],
		]);

		return $dataProvider;
	}

	/**
	 * @return array
	 */
	public static function getRoleByPermission($permissionName)
	{
		$roles = Yii::$app->authManager->getRoles();

		$array = [];
		foreach ($roles as $role) {
			$permissions = Yii::$app->authManager->getPermissionsByRole($role->name);
			foreach ($permissions as $permission) {
				if ($permissionName == $permission->name) {
					$array[] = $role->name;
				}
			}

		}

		return $array;
	}

	/**
	 * @param $parent
	 *
	 * @return mixed|string
	 */
	public static function getInherit($parent)
	{
		$array = [];
		if ($children = Yii::$app->authManager->getChildren($parent)) {
			foreach ($children as $child) {
				if ($child->type == Item::TYPE_ROLE) {
					$array[] = $child->name;
				}
			}
		}
		return $array;
	}
}
