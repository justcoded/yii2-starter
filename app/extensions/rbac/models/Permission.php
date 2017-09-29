<?php

namespace justcoded\yii2\rbac\models;

use Yii;

class Permission extends Item
{

	/**
	 * @return bool
	 */
	public function store($data)
	{

		if(!$permission = Yii::$app->authManager->getPermission($data->name)){
			$permission = Yii::$app->authManager->createPermission($data->name);
			$permission->description = $data->description;
			if(!Yii::$app->authManager->add($permission)){
				return false;
			}
		}else{
			$permission->description = $data->description;
			Yii::$app->authManager->update($data->name, $permission);
		}

//		if(!empty($data->rule_name)){
//
//			if (!Yii::$app->authManager->getRule($data->rule_name)) {
//
////				$rule = Yii::createObject(['class' => Item::class], $data);
////				pa($rule,1);
//				pa(Yii::$app->authManager->add($data),1 );
//			}
//		}

		$this->storeParentRoles($data);

		$this->storeParentPermissions($data);

		$this->storeChildrenPermissions($data);

		return true;
	}


	/**
	 * @param $data
	 * @return bool
	 */
	public function storeParentRoles($data)
	{
		$permission = Yii::$app->authManager->getPermission($data->name);

		$old_parent_roles = $this->getParentRoles($data);
		if (!empty($old_parent_roles) && is_array($old_parent_roles)){
			foreach ($old_parent_roles as $parent_role){
				Yii::$app->authManager->removeChild($parent_role, $permission);
			}
		}

		if (empty($data->parent_roles)){
			return true;
		}

		$array_parent_roles = explode(',', $data->parent_roles);

		if (!empty($array_parent_roles) && is_array($array_parent_roles)) {
			foreach ($array_parent_roles as $role) {
				$parent_role = Yii::$app->authManager->getRole($role);
				Yii::$app->authManager->addChild($parent_role, $permission);
			}
		}

		return true;
	}

	/**
	 * @param $data
	 * @return bool
	 */
	public function storeParentPermissions($data)
	{
		$permission = Yii::$app->authManager->getPermission($data->name);

		$old_parent_permissions = $this->getParentPermissions($data);
		if (!empty($old_parent_permissions) && is_array($old_parent_permissions)){
			foreach ($old_parent_permissions as $parent_permission){
				Yii::$app->authManager->removeChild($parent_permission, $permission);
			}
		}

		if (empty($data->parent_permissions)){
			return true;
		}

		$array_parent_permissions = explode(',', $data->parent_permissions);

		if (!empty($array_parent_permissions) && is_array($array_parent_permissions)) {
			foreach ($array_parent_permissions as $permission_name) {
				$parent_permission = Yii::$app->authManager->getPermission($permission_name);
				Yii::$app->authManager->addChild($parent_permission, $permission);
			}
		}

		return true;
	}

	/**
	 * @param $data
	 * @return bool
	 */
	public function storeChildrenPermissions($data)
	{
		$parent_permission = Yii::$app->authManager->getPermission($data->name);
		Yii::$app->authManager->removeChildren($parent_permission);

		if (empty($data->children_permissions)){
			return true;
		}

		$array_children_permissions = explode(',', $data->children_permissions);

		if (!empty($array_children_permissions) && is_array($array_children_permissions)) {
			foreach ($array_children_permissions as $permission) {
				$child_permission = Yii::$app->authManager->getPermission($permission);
				Yii::$app->authManager->addChild($parent_permission, $child_permission);
			}
		}

		return true;
	}

	/**
	 * @param $data
	 * @return array|bool
	 */
	public function getParentPermissions($data)
	{
		$permissions = Item::findPermissionsWithChildItem();

		if (empty($permissions) || !is_array($permissions)){
			return false;
		}

		$parent_permissions = [];
		foreach ($permissions as $name_permission => $permission){
			foreach ($permission->data as $child_name => $child){
				if ($child->name == $data->name){
					$parent_permissions[$name_permission] = $permission;
				}
			}
		}

		return $parent_permissions;
	}

	/**
	 * @param $data
	 * @return array|bool
	 */
	public function getParentRoles($data)
	{
		$roles = Item::findRolesWithChildItem();

		if (empty($roles) || !is_array($roles)){
			return false;
		}

		$parent_roles = [];
		foreach ($roles as $name_role => $role){
			foreach ($role->data as $child_name => $child){
				if ($child->name == $data->name){
					$parent_roles[$name_role] = $role;
				}
			}
		}

		return $parent_roles;
	}

}