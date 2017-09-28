<?php

namespace justcoded\yii2\rbac\forms;

use yii\helpers\ArrayHelper;
use yii\rbac\Role;
use Yii;


class RoleForm extends BaseForm
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
			['name', 'uniqueName', 'on' => static::SCENARIO_CREATE],
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
	 * @param $value
	 * @return mixed
	 */
	public function setInheritPermissions($value)
	{
		return $this->inherit_permissions = $value;
	}


	#TODO refactor
	/**
	 * @return bool|null|string
	 */
	public function getAllowPermissions()
	{
		return false;

		$allow_permissions = $this->findRolesWithChildItem();

		if(empty($allow_permissions) || !is_array($allow_permissions)){
			return null;
		}

		$permissions = '';
		foreach ($allow_permissions as $permission){
			foreach ($permission->data as $child)
				if ($child->type == Role::TYPE_PERMISSION) {
					$permissions .= $permission->name . ',';
				}
		}

		return substr($permissions, 0, -1);
	}

	/**
	 * @param $value
	 * @return mixed
	 */
	public function setAllowPermissions($value)
	{
		return $this->allow_permissions = $value;
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
