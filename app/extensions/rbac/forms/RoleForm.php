<?php

namespace justcoded\yii2\rbac\forms;

use justcoded\yii2\rbac\models\Item;
use yii\helpers\ArrayHelper;
use yii\rbac\Role;
use Yii;


class RoleForm extends ItemForm
{

	public $allow_permissions;
	public $deny_permissions;
	public $inherit_permissions;
	public $role;
	public $permissions;
	public $permissions_search;


	/**
	 * @inheritdoc
	 * @return array
	 */
	public function rules()
	{
		return  ArrayHelper::merge(parent::rules(),[
			[['allow_permissions', 'deny_permissions', 'permissions', 'inherit_permissions'], 'safe']
		]);
	}


	/**
	 * @param $attribute
	 * @return bool
	 */
	public function uniqueName($attribute)
	{

		if (Yii::$app->authManager->getRole($this->attributes['name'])) {
			$this->addError($attribute, 'Name must be unique');

			return false;
		}

		return true;
	}

	/**
	 * @return bool
	 */
	public function beforeValidate()
	{
		$this->type = Role::TYPE_ROLE;
		$this->permissions = explode(',', $this->allow_permissions);
		return parent::beforeValidate();
	}


	/**
	 * @return array|bool
	 */
	public function getInheritPermissions()
	{
		if(empty($this->name)){
			return false;
		}

		$child = Yii::$app->authManager->getChildRoles($this->name);
		ArrayHelper::remove($child, $this->name);

		return ArrayHelper::map($child, 'name', 'name');
	}

	/**
	 * @return bool|null|string
	 */
	public function getAllowPermissions()
	{

		if ($this->name){
			$permissions = Yii::$app->authManager->getPermissionsByRole($this->name);
		}else{
			$permissions = Yii::$app->authManager->getPermissions();
		}

		if (empty($permissions) || !is_array($permissions)){
			return false;
		}

		$permissions_name = implode(',', array_keys($permissions));


		return $permissions_name;
	}


	public function arrayAllowPermissions()
	{
		$permissions = Yii::$app->authManager->getPermissions();

		if(!$permissions){
			return false;
		}
		ArrayHelper::remove($permissions, '*');

		$data = [];
		foreach ($permissions as $name => $permission){
			if (substr($name, -1) == '*'){
				ArrayHelper::remove($permissions, $name);
				$data[$name] = [];
			}
		}

		foreach ($permissions as $name => $permission) {
			foreach ($data as $parent_name => $perm) {
				$cut_name = substr($parent_name, 0,-1);

				$pattern = '/' . addcslashes($cut_name,'/') . '/';

				if (preg_match($pattern, $name)) {
					$data[$parent_name][] = $name;
					ArrayHelper::remove($permissions, $name);
				}
			}
		}

		foreach ($permissions as $name => $permission){
			ArrayHelper::remove($permissions, $name);
			$data[$name] = [];
		}

		return $data;
	}

	/**
	 * @return string
	 */
	public function treeAllowPermissions()
	{
		$html = '';
		foreach ($this->arrayAllowPermissions() as $parent => $children){
			$html .= '<li class="permissions">'. $parent;
			if (!empty($children)){
				$html .= '<ul>';
					foreach ($children as $child){
						$html .= "<li class='permissions'>$child</li>";
					}
				$html .= '</ul>';
			}
			$html .= '</li>';
		}
		return $html;
	}


	/**
	 * @return mixed
	 */
	public function getListInheritPermissions()
	{
		$roles = $this->rolesList;
		ArrayHelper::remove($roles, $this->name);

		return $roles;
	}
}
