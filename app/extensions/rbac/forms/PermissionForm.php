<?php

namespace justcoded\yii2\rbac\forms;

use justcoded\yii2\rbac\models\Item;
use justcoded\yii2\rbac\models\Permission;
use yii\rbac\Role as RbacRole;
use yii\rbac\Permission as RbacPermission;
use Yii;
use yii\helpers\ArrayHelper;


class PermissionForm extends ItemForm
{

	public $parent_roles;
	public $parent_permissions;
	public $children_permissions;

	public $parent_roles_search;
	public $parent_permissions_search;
	public $children_permissions_search;

	/**
	 * @inheritdoc
	 */
	public function init()
	{
		$this->type = RbacPermission::TYPE_PERMISSION;
	}

	/**
	 * @inheritdoc
	 * @return array
	 */
	public function rules()
	{
		return ArrayHelper::merge(parent::rules(), [
			['name', 'match', 'pattern' => '/^[a-z\-\/]*$/'],
			['ruleName', 'match', 'pattern' => '/^[a-z][\w\-\\\]*$/i'],
			['ruleName', 'validRuleClass', 'skipOnEmpty' => true],
			[['parent_roles', 'parent_permissions', 'children_permissions'], 'string'],
		]);
	}

	/**
	 * @inheritdoc
	 */
	public function uniqueItemName($attribute, $params, $validator)
	{
		$permission = Permission::getList();
		return ! isset($permission[$this->$attribute]);
	}

	/**
	 * Validate Rule Class to be namespaced class name and instance of yii\rbac\Rule
	 *
	 * @param string $attribute
	 * @param array  $params
	 * @param mixed  $validator
	 *
	 * @return bool
	 */
	public function validRuleClass($attribute, $params, $validator)
	{
		$class = $this->$attribute;
		if (! class_exists($class)) {
			$this->addError($attribute, 'Not valid class name.');
			return false;
		} else {
			$reflect = new \ReflectionClass($class);
			if (! $reflect->isSubclassOf(\yii\rbac\Rule::className())) {
				$this->addError($attribute, 'Class have to be extended of \\yii\\rbac\\Rule class');
				return false;
			}
		}

		return true;
	}

	/**
	 * @return string
	 */
	public function getParentRolesString()
	{
		$roles = $this->findRolesWithChildItem();

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
	 * @return string
	 */
	public function getParentPermissionsString()
	{
		$permissions = $this->findPermissionsWithChildItem();

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
	 * @return string
	 */
	public function getChildrenPermissionsString()
	{
		$permissions = Yii::$app->authManager->getChildren($this->name);

		return implode(',', array_keys($permissions));
	}

	/**
	 * @return bool
	 */
	public function store()
	{

		if(!$permission = Yii::$app->authManager->getPermission($this->name)){
			$permission = Yii::$app->authManager->createPermission($this->name);
			$permission->description = $this->description;
			if (!empty($this->ruleName)){
				$permission->ruleName = $this->ruleName;
			}
			if(!Yii::$app->authManager->add($permission)){
				return false;
			}
		}else{
			$permission->description = $this->description;

			$permission->ruleName = (!empty($this->ruleName)) ? $this->ruleName : null;

			Yii::$app->authManager->update($this->name, $permission);
		}

		$this->storeParentRoles();
		$this->storeParentPermissions();
		$this->storeChildrenPermissions();

		return true;
	}


	/**
	 * @return bool
	 */
	public function storeParentRoles()
	{
		$permission = Yii::$app->authManager->getPermission($this->name);

		$this->removeChildrenArray($this->parentRoles, $permission);

		if (!empty($this->parent_roles)){
			return $this->addChildrenArray(explode(',', $this->parent_roles), ['child' => $permission], false);
		}

		return true;
	}

	/**
	 * @return bool
	 */
	public function storeParentPermissions()
	{
		$permission = Yii::$app->authManager->getPermission($this->name);

		$this->removeChildrenArray($this->parentPermissions, $permission);

		if (!empty($this->parent_permissions)){
			return $this->addChildrenArray(explode(',', $this->parent_permissions), ['child' => $permission]);
		}

		return true;
	}

	/**
	 * @return bool
	 */
	public function storeChildrenPermissions()
	{
		$permission = Yii::$app->authManager->getPermission($this->name);
		Yii::$app->authManager->removeChildren($permission);

		if (!empty($this->children_permissions)){
			return $this->addChildrenArray(explode(',', $this->children_permissions), ['parent' => $permission]);
		}

		return true;
	}

	/**
	 * @return array
	 */
	public function getParentPermissions()
	{
		$permissions = $this->findPermissionsWithChildItem();

		$parent_permissions = [];
		foreach ($permissions as $name_permission => $permission){
			foreach ($permission->data as $child_name => $child){
				if ($child->name == $this->name){
					$parent_permissions[$name_permission] = $permission;
				}
			}
		}

		return $parent_permissions;
	}

	/**
	 * @return array
	 */
	public function getParentRoles()
	{
		$roles = $this->findRolesWithChildItem();

		$parent_roles = [];
		foreach ($roles as $name_role => $role){
			foreach ($role->data as $child_name => $child){
				if ($child->name == $this->name){
					$parent_roles[$name_role] = $role;
				}
			}
		}

		return $parent_roles;
	}

	/**
	 * @return \yii\rbac\Role[]
	 */
	public function findRolesWithChildItem()
	{
		$data = Yii::$app->authManager->getRoles();

		foreach ($data as $role => $value){
			$data[$role]->data = Yii::$app->authManager->getChildren($value->name);
		}

		return $data;
	}

	/**
	 * @return \yii\rbac\Permission[]
	 */
	public function findPermissionsWithChildItem()
	{
		$data = Yii::$app->authManager->getPermissions();

		foreach ($data as $permission => $value){
			$data[$permission]->data = Yii::$app->authManager->getChildren($value->name);
		}

		return $data;
	}

}