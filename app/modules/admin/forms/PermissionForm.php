<?php

namespace app\modules\admin\forms;

use app\models\AuthItems;
use yii\rbac\Role;


class PermissionForm extends AuthItems
{
//	public $name;
//	public $description;
//	public $rule_name;
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
		return  [
			[['type', 'name'], 'required'],
			//['name', 'unique','targetAttribute' => ['name'], 'message' => 'Username must be unique.'],
			[['name', 'description', 'rule_name', 'data'], 'string'],
			[['type'], 'integer'],
			[['parent_roles', 'parent_permissions', 'children_permissions'], 'string'],
		];
	}


	/**
	 * @return bool
	 */
	public function beforeValidate()
	{
		$this->type = Role::TYPE_PERMISSION;

		return parent::beforeValidate();
	}

}