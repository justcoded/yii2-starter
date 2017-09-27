<?php

namespace justcoded\yii2\rbac\forms;

use yii\base\Model;
use yii\helpers\ArrayHelper;
use yii\rbac\Role;
use Yii;


class RoleForm extends Model
{
	const SCENARIO_CREATE = 'create';

	public $name;
	public $type;
	public $description;
	public $rule_name;
	public $data;
	public $created_at;
	public $updated_at;

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
		return  [
			[['type', 'name'], 'required'],
			[['name', 'description', 'rule_name', 'data'], 'string'],
			[['type', 'created_at', 'updated_at'], 'integer'],
			['name', 'uniqueName', 'on' => static::SCENARIO_CREATE],
			[['allow_permissions', 'deny_permissions', 'permissions', 'inherit_permissions'], 'safe']
		];
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
	 * @param $name
	 */
	public function setRole($name)
	{
		$this->role = Yii::$app->authManager->getRole($name);
	}

	/**
	 * @return null|Role
	 */
	public function getRole()
	{
		return Yii::$app->authManager->getRole($this->name);
	}

	/**
	 * @param $name
	 */
	public function setName($name)
	{
		$this->name = $name;
	}

	/**
	 * @return string
	 */
	public function getName()
	{
		return $this->getRole()->name;
	}

	/**
	 *
	 */
	public function setDescription($description)
	{
		$this->description = $description;
	}

	/**
	 * @return string
	 */
	public function getDescription()
	{
		return $this->getRole() ? $this->getRole()->description : '';
	}

	/**
	 *
	 */
	public function setType()
	{
		$this->type = $this->getRole()->type;
	}

	/**
	 * @return int
	 */
	public function getType()
	{
		return $this->getRole()->type;
	}

	/**
	 *
	 */
	public function setRule_name()
	{
		$this->rule_name = $this->getRole()->rule_name;
	}

	/**
	 * @return mixed
	 */
	public function getRule_name()
	{
		return $this->getRole()->rule_name;
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

	/**
	 * @return bool|null|string
	 */
	public function getAllowPermissions()
	{
		return false;

		$allow_permissions = $this->findWithChildItem();

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
	 * @return Role[]
	 */
	public function findWithChildItem()
	{
		$data = Yii::$app->authManager->getRoles();

		foreach ($data as $role => $value){
			$data[$role]->data = Yii::$app->authManager->getChildren($value->name);
		}

		return $data;
	}
}
