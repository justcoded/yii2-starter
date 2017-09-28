<?php

namespace justcoded\yii2\rbac\models;

use Yii;


class Role extends Item
{

	/**
	 * @param $role_name
	 * @return int|null
	 */
	public static function countPermissionsByRole($role_name)
	{
		$permissions = Yii::$app->authManager->getPermissionsByRole($role_name);
		if (!is_array($permissions)) {
			return null;
		}

		return count($permissions);
	}

	/**
	 * @param $parent
	 * @return mixed|string
	 */
	public static function getInherit($parent)
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
			$new_role->description = $data->description;

			if(!Yii::$app->authManager->add($new_role)){
				return false;
			}
		}else{
			$new_role->description = $data->description;
			Yii::$app->authManager->update($name, $new_role);
		}

		$new_role = Yii::$app->authManager->getRole($name);
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