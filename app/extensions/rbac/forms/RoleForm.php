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
		if (Yii::$app->authManager->getRole($this->getAttributes()['name'])) {
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

		$permissions_name = implode(',', array_keys($permissions));

		return $permissions_name;
	}


	/**
	 * @return string
	 */
	public function treeDennyPermissions()
	{
		$permissions = Yii::$app->authManager->getPermissions();

		if (!empty($this->name)){
			$allow_permissions =Yii::$app->authManager->getPermissionsByRole($this->name);
			foreach ($allow_permissions as $name => $item) {
				unset($permissions[$name]);
			}
		}

		return $this->treePermissions($permissions);
	}

	/**
	 * @return string
	 */
	public function treeAllowPermissions()
	{
		$permissions =Yii::$app->authManager->getPermissionsByRole($this->name);
		
		return $this->treePermissions($permissions);
	}

	/**
	 * @return string
	 */
	public function treePermissions(array $permissions)
	{
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

		$html = '';
		foreach ($data as $parent => $children){
			$html .= '<li class="permissions" data-name='.$parent.'>'. $parent;
			if (!empty($children)){
				$html .= '<ul>';
					foreach ($children as $child){
						$html .= "<li>$child</li>";
					}
				$html .= '</ul>';
			}
			$html .= '</li>';
		}
		return $html;
	}


	/**
	 * @return array
	 */
	public function getListInheritPermissions()
	{
		$roles = Yii::$app->authManager->getRoles();
		$array_roles = ArrayHelper::map($roles, 'name', 'name');
		ArrayHelper::remove($array_roles, $this->name);

		return $array_roles;
	}

	/**
	 * @return bool
	 */
	public function store()
	{
		if(!$new_role = Yii::$app->authManager->getRole($this->name)){
			$new_role = Yii::$app->authManager->createRole($this->name);
			$new_role->description = $this->description;

			if(!Yii::$app->authManager->add($new_role)){
				return false;
			}
		}else{
			$new_role->description = $this->description;
			Yii::$app->authManager->update($this->name, $new_role);
		}

		$new_role = Yii::$app->authManager->getRole($this->name);
		Yii::$app->authManager->removeChildren($new_role);

		if ($this->inherit_permissions){
			$this->addChildrenArray($this->inherit_permissions, ['parent' => $new_role], false);
		}

		if ($this->allow_permissions) {
			$this->permissions = explode(',', $this->allow_permissions);
			$this->addChildrenArray($this->permissions, ['parent' => $new_role]);
		}

		return true;
	}
}
