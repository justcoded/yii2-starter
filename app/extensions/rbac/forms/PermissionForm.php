<?php

namespace justcoded\yii2\rbac\forms;

use justcoded\yii2\rbac\models\AuthItems;
use yii\base\Model;
use yii\rbac\Role;
use justcoded\yii2\rbac\models\AuthItemChild;
use justcoded\yii2\rbac\models\AuthRule;
use ErrorException;
use Yii;
use yii\helpers\ArrayHelper;


class PermissionForm extends Model
{
	const SCENARIO_CREATE = 'create';

	public $name;
	public $type;
	public $description;
	public $rule_name;
	public $data;
	public $created_at;
	public $updated_at;

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
		return [
			[['type', 'name'], 'required'],
			[['name', 'description', 'rule_name', 'data'], 'string'],
			[['type', 'created_at', 'updated_at'], 'integer'],
			['name', 'uniqueName', 'on' => static::SCENARIO_CREATE],
			['rule_name', 'match', 'pattern' => '/^[a-z][\w\-\/]*$/i'],
			[['parent_roles', 'parent_permissions', 'children_permissions'], 'string'],
		];
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
	 * @return bool|string
	 */
	public function getParentRoles()
	{
		$roles = AuthItemChild::find()
			->select('parent')
			->with('parentItem')
			->where(['child' => $this->name])
			->asArray()
			->all();

		if (!is_array($roles) || empty($roles)){
			return false;
		}

		$string_roles = '';
		foreach ($roles as $role){
			if ($role['parentItem']['type'] == Role::TYPE_ROLE) {
				$string_roles .= $role['parent'] . ',';
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

		$permissions = AuthItemChild::find()
			->select('parent')
			->with('parentItem')
			->where(['child' => $this->name])
			->asArray()
			->all();

		if (!is_array($permissions) || empty($permissions)){
			return false;
		}

		$string_permissions = '';
		foreach ($permissions as $permission){
			if ($permission['parentItem']['type'] == Role::TYPE_PERMISSION) {
				$string_permissions .= $permission['parent'] . ',';
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
		$permissions = AuthItemChild::find()
			->select('child')
			->where(['parent' => $this->name])
			->asArray()
			->all();

		if (!is_array($permissions) || empty($permissions)){
			return false;
		}
		$string_permissions = '';
		foreach ($permissions as $permission){
			$string_permissions .= $permission['child']. ',';
		}

		return substr($string_permissions, 0, -1);
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
	 * @param $value
	 * @return mixed
	 */
	public function setChildrenPermissions($value)
	{
		return $this->children_permissions = $value;
	}

	/**
	 * @return bool
	 */
	public function store()
	{
		if( isset($this->rule_name) && !empty($this->rule_name)){
			if (!AuthRule::findOne($this->rule_name)) {

				$new_rule = new AuthRule([
					'name' => $this->rule_name
				]);

				if (!$new_rule->save()) {
					throw new ErrorException($this->errors);
				}
			}
		}

		if (!$this->save()){
			throw new ErrorException($this->errors);
		}

		$name = $this->name;

		$this->storeParentRoles($name);

		$this->storeParentPermissions($name);

		$this->storeChildrenPermissions($name);

		return true;
	}

	/**
	 * @param $name
	 * @return bool
	 */
	public function storeParentRoles($name)
	{
		$old_parent_roles = explode(',', $this->parentRoles);
		$array_parent_roles = explode(',', $this->parent_roles);

		foreach ($array_parent_roles as $role) {
			if (!AuthItemChild::find()->where(['parent' => $role, 'child' => $name])->all()) {
				$new_child = new AuthItemChild([
					'parent' => $role,
					'child'  => $name
				]);
				$new_child->save();
			}

			foreach ($old_parent_roles as $key => $value){
				if ($value == $role) {
					unset($old_parent_roles[$key]);
				}
			}
		}

		if (!empty($old_parent_roles)) {
			foreach ($old_parent_roles as $role) {
				if($role_for_remove = AuthItemChild::find()->where(['parent' => $role, 'child' => $name])->one()) {
					$role_for_remove->delete();
				}
			}
		}

		return true;
	}

	/**
	 * @param $name
	 * @return bool
	 */
	public function storeParentPermissions($name)
	{
		$old_parent_permissions = explode(',', $this->parentPermissions);
		$array_parent_permissions = explode(',', $this->parent_permissions);

		foreach ($array_parent_permissions as $permission) {
			if (!AuthItemChild::find()->where(['parent' => $permission, 'child' => $name])->all()) {
				$new_child = new AuthItemChild([
					'parent' => $permission,
					'child'  => $name
				]);
				$new_child->save();
			}

			foreach ($old_parent_permissions as $key => $value){
				if ($value == $permission) {
					unset($old_parent_permissions[$key]);
				}
			}
		}

		if (!empty($old_parent_permissions)) {
			foreach ($old_parent_permissions as $permission) {
				if($permission_for_remove = AuthItemChild::find()->where(['parent' => $permission, 'child' => $name])->one()) {
					$permission_for_remove->delete();
				}
			}
		}

		return true;
	}

	/**
	 * @param $name
	 * @return bool
	 */
	public function storeChildrenPermissions($name)
	{
		$old_children_permissions = explode(',', $this->childrenPermissions);
		$array_children_permissions = explode(',', $this->children_permissions);

		foreach ($array_children_permissions as $permission) {
			if (!AuthItemChild::find()->where(['parent' => $name, 'child' => $permission])->all()) {
				$new_child = new AuthItemChild([
					'parent' => $name,
					'child'  => $permission,
				]);
				$new_child->save();
			}

			foreach ($old_children_permissions as $key => $value){
				if ($value == $permission) {
					unset($old_children_permissions[$key]);
				}
			}
		}

		if (!empty($old_children_permissions)) {
			foreach ($old_children_permissions as $permission) {
				if($permission_for_remove = AuthItemChild::find()->where(['parent' => $name, 'child' => $permission])->one()) {
					$permission_for_remove->delete();
				}
			}
		}

		return true;
	}

}