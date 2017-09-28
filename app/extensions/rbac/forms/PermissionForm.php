<?php

namespace justcoded\yii2\rbac\forms;

use justcoded\yii2\rbac\models\Item;
use yii\rbac\Role;
use Yii;
use yii\helpers\ArrayHelper;


class PermissionForm extends BaseForm
{

	public $parent_roles;
	public $parent_permissions;
	public $children_permissions;

	public $parent_roles_search;
	public $parent_permissions_search;
	public $children_permissions_search;

	/**
	 * @inheritdoc
	 * @return array
	 */
	public function rules()
	{
		return ArrayHelper::merge(parent::rules(), [
			['name', 'uniqueName', 'on' => static::SCENARIO_CREATE],
			['rule_name', 'match', 'pattern' => '/^[a-z][\w\-\/]*$/i'],
			[['parent_roles', 'parent_permissions', 'children_permissions'], 'string'],
		]);
	}

	/**
	 * @param $attribute
	 * @return bool
	 */
	public function uniqueName($attribute)
	{
		if (Yii::$app->authManager->getPermission($this->attributes['name'])) {
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
		$this->type = Role::TYPE_PERMISSION;
		if(empty($this->rule_name)){
			$this->rule_name = null;
		}

		return parent::beforeValidate();
	}

	/**
	 * @return null|\yii\rbac\Permission
	 */
	public function getPermission()
	{
		return Yii::$app->authManager->getPermission($this->name);
	}

	/**
	 * @return string
	 */
	public function getDescription()
	{
		return $this->getPermission() ? $this->getPermission()->description : '';
	}

	/**
	 * @return bool|string
	 */
	public function getParentRoles()
	{
		$roles = Item::findRolesWithChildItem();

		if (empty($roles) || !is_array($roles)){
			return false;
		}

		$string_roles = '';
		foreach ($roles as $role_name => $role){
			foreach ($role->data as $child_name => $child){
				if ($child->name == $this->name){
					$string_roles .= $role_name .',';
				}
			}
		}

		return substr($string_roles, 0, -1);
	}

	/**
	 * @param $value
	 * @return mixed
	 */
	public function setParentRoles($value)
	{
		return $this->parent_roles = $value;
	}

	/**
	 * @return bool|string
	 */
	public function getParentPermissions()
	{
		$permissions = Item::findPermissionsWithChildItem();

		if (empty($permissions) || !is_array($permissions)){
			return false;
		}

		$string_permissions = '';
		foreach ($permissions as $name_permissions => $permission){
			foreach ($permission->data as $child_name => $child){
				if ($child->name == $this->name){
					$string_permissions .= $name_permissions .',';
				}
			}
		}

		return substr($string_permissions, 0, -1);
	}

	/**
	 * @param $value
	 * @return mixed
	 */
	public function setParentPermissions($value)
	{
		return $this->parent_permissions = $value;
	}

	/**
	 * @return bool|string
	 */
	public function getChildrenPermissions()
	{
		$permissions = Yii::$app->authManager->getChildren($this->name);

		if (!is_array($permissions) || empty($permissions)){
			return false;
		}

		return implode(',', array_keys($permissions));
	}

	/**
	 * @param $value
	 * @return mixed
	 */
	public function setChildrenPermissions($value)
	{
		return $this->children_permissions = $value;
	}

}