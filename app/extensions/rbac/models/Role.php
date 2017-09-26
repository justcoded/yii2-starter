<?php

namespace justcoded\yii2\rbac\models;

use Yii;
use yii\helpers\ArrayHelper;

class Role extends Item
{
	/**
	 * @return mixed
	 */
	public function getChildItem()
	{
		return $this->hasOne(AuthItemChild::className(), ['parent' => 'name']);
	}

	/**
	 * @return $this
	 */
	public static function getRoles()
	{
		return static::find()->where(['type' => Role::TYPE_ROLE]);
	}


	/**
	 * @return array|bool
	 */
	public function getPermissionsList()
	{
		$data = Yii::$app->authManager->getPermissions();

		if (!is_array($data)){
			return false;
		}

		return ArrayHelper::map($data, 'name', 'name');
	}


	/**
	 * @return array|bool
	 */
	public function getRolesList()
	{
		$data = Yii::$app->authManager->getRoles();

		if (!is_array($data)){
			return false;
		}

		return ArrayHelper::map($data, 'name', 'name');
	}


	/**
	 * @param $role_name
	 * @return \yii\rbac\Permission[]
	 */
	public function getPermissionsByRole($role_name)
	{
		return Yii::$app->authManager->getPermissionsByRole($role_name);
	}

	/**
	 * @param $role_name
	 * @return int|null
	 */
	public function countPermissionsByRole($role_name)
	{
		$permissions = $this->getPermissionsByRole($role_name);
		if (!is_array($permissions)) return null;
		return count($permissions);
	}

	/**
	 * @param $parent
	 * @return mixed|string
	 */
	public function getInherit($parent)
	{
		if($children = Yii::$app->authManager->getChildren($parent)){
			foreach ($children as $child){
				if($child->type == static::TYPE_ROLE){
					return $child->name;
				}
			}
		}
	}

	/**
	 * @return array
	 */
	public  static function getRoleByPermission()
	{
		$roles = Yii::$app->authManager->getRoles();

		$array = [];
		foreach ($roles as $role){
			$permissions = Yii::$app->authManager->getPermissionsByRole($role->name);
			foreach ($permissions as $permission) {
				if(!isset($array[$permission->name])){
					$array[$permission->name] = '';
				}
				$array[$permission->name] .= $role->name.'<br>';
			}

		}
		return $array;
	}
}