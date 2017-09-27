<?php

namespace justcoded\yii2\rbac\models;

use Yii;
use yii\helpers\ArrayHelper;

class Role extends Item
{
	/**
	 * @return mixed
	 */
//	public function getChildItem()
//	{
//		return $this->hasOne(AuthItemChild::className(), ['parent' => 'name']);
//	}

	/**
	 * @return $this
	 */
//	public static function getRoles()
//	{
//		return static::find()->where(['type' => Role::TYPE_ROLE]);
//	}








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

	/**
	 * @param $data
	 * @return bool
	 */
	public function store($data)
	{
		$name = $data->name;

		if(!$new_role = Yii::$app->authManager->getRole($name)){
			$new_role = Yii::$app->authManager->createRole($name);

			if(!Yii::$app->authManager->add($new_role)){
				return false;
			}
			$new_role = Yii::$app->authManager->getRole($name);
		}else{
			$new_role->description = $data->description;
			Yii::$app->authManager->update($name, $new_role);
		}

		Yii::$app->authManager->removeChildren($new_role);
		if ($data->inherit_permissions){
			foreach ($data->inherit_permissions as $role){
				$child_role = Yii::$app->authManager->getRole($role);
				Yii::$app->authManager->addChild($new_role, $child_role);
			}
		}

		if ($data->permissions) {
			foreach ($data->permissions as $permission) {
				$child_permission = Yii::$app->authManager->getPermission($permission);
				Yii::$app->authManager->addChild($new_role, $child_permission);

			}
		}

//		if ($data->deny_permissions){
//			$deny_permissions = explode(',', $data->deny_permissions);
//			foreach ($deny_permissions as $permission) {
//				if($permission_for_remove = AuthItemChild::find()->where(['parent' => $name, 'child' => $permission])->one()) {
//					$permission_for_remove->delete();
//				}
//			}
//		}


		return true;
	}
}