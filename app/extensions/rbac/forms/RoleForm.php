<?php

namespace justcoded\yii2\rbac\forms;

use justcoded\yii2\rbac\models\Item;
use justcoded\yii2\rbac\models\Permission;
use justcoded\yii2\rbac\models\Role;
use yii\helpers\ArrayHelper;
use yii\rbac\Role as RbacRole;
use Yii;


class RoleForm extends ItemForm
{
	/**
	 * @var string[]
	 */
	public $childRoles = [];

	/**
	 * @var string[]
	 */
	public $allowPermissions = [];

	/**
	 * @var string[]
	 */
	protected $inheritPermissions = [];

	/**
	 * @var Role
	 */
	protected $role;

	/**
	 * @inheritdoc
	 */
	public function init()
	{
		$this->type = RbacRole::TYPE_ROLE;
		$this->role = new Role();
	}

	/**
	 * @inheritdoc
	 * @return array
	 */
	public function rules()
	{
		return  ArrayHelper::merge(parent::rules(), [
			[['childRoles', 'allowPermissions'], 'each', 'rule' => ['string']],
		]);
	}

	/**
	 * @inheritdoc
	 * @return array
	 */
	public function attributeLabels()
	{
		return array_merge(parent::attributeLabels(), [
			'childRoles' => 'Inherit Roles'
		]);
	}

	/**
	 * @inheritdoc
	 * @return array
	 */
	public function attributeHints()
	{
		return [
			'childRoles' => 'You can inherit other roles to have the same permissions as other roles. <br> 
				Allowed Permissions box will be updated with inherited permissions once you save changes.',
		];
	}

	/**
	 * @inheritdoc
	 */
	public function uniqueItemName($attribute, $params, $validator)
	{
		$permission = Role::getList();
		return ! isset($permission[$this->$attribute]);
	}

	/**
	 * Setter for $role
	 * @param Role $role
	 */
	public function setRole(Role $role)
	{
		$this->role = $role;
		$this->load((array)$role->getItem(), '');

		$childRoles = Yii::$app->authManager->getChildRoles($this->name);
		$this->childRoles = array_diff(array_keys($childRoles), [$this->name]);

		$this->allowPermissions = Role::getPermissionsRecursive($this->name);
		$this->inheritPermissions = $this->getInheritPermissions();
	}

	/**
	 * Main form process method
	 *
	 * @return bool
	 */
	public function save()
	{
		if (! $this->validate()) {
			return false;
		}

		if (! $item = $this->role->getItem()) {
			$item = Role::create($this->name, $this->description);
		}

		$item->description = $this->description;
		$updated = Yii::$app->authManager->update($item->name, $item);

		// clean relations
		Yii::$app->authManager->removeChildren($item);

		// set relations from input
		Role::addChilds($item, $this->childRoles, Role::TYPE_ROLE);

		$allow = $this->getCleanAllowPermissions();
		Role::addChilds($item, $allow, Role::TYPE_PERMISSION);

		return $updated;
	}

	/**
	 * Get array of inherit permissions from child Roles
	 *
	 * @return string[]
	 */
	public function getInheritPermissions()
	{
		$herited = [];
		foreach ($this->childRoles as $roleName) {
			$permissions = Yii::$app->authManager->getPermissionsByRole($roleName);
			$herited = array_merge(
				$herited,
				array_keys($permissions)
			);
		}

		$herited = array_unique($herited);
		$herited = array_combine($herited, $herited);
		return $herited;
	}

	/**
	 * List of available deny permissions
	 * (all permissions without allow/inherit permissions)
	 *
	 * @return string[]
	 */
	public function getDenyPermissions()
	{
		$permissions = Permission::getList();
		$permissions = array_diff($permissions, $this->allowPermissions, $this->inheritPermissions);
		return $permissions;
	}

	/**
	 * Clean allowPermissions from inherit Permissions and recursive childs, which should not be added to RBAC relations
	 *
	 * @return string[]
	 */
	public function getCleanAllowPermissions()
	{
		$allowPermissions = array_diff($this->allowPermissions, $this->inheritPermissions);
		list($parents, $children) = Permission::getParentChildMap($allowPermissions);

		$cleanPermissions = array_combine($allowPermissions, $allowPermissions);
		foreach ($parents as $child => $childParents) {
			if (isset($cleanPermissions[$child])
				&& array_intersect($childParents, $allowPermissions)
			) {
				unset($cleanPermissions[$child]);
			}
		}

		return $cleanPermissions;
	}

	/**
	 * Prepare linear tree array with depth and weight parameters
	 *
	 * @param string[] $permissions
	 *
	 * @return array
	 */
	public function getLinearTree($permissions)
	{
		if (empty($permissions)) {
			return [];
		}

		list($parents, $children) = Permission::getParentChildMap($permissions);

		$tree = $this->buildLinearTree($permissions, $permissions, $children);

		return $tree;
	}

	/**
	 * Recursive function to go over tree and sort/move items correctly.
	 *
	 * @param array $array
	 * @param array $items
	 * @param array $children
	 * @param int $depth
	 *
	 * @return array
	 */
	protected function buildLinearTree($array, &$items, &$children, $depth = 0)
	{
		static $position;

		$tree = [];
		foreach ($array as $item) {
			if (! isset($items[$item])) {
				continue;
			}

			$tree[$item] = [
				'name' => $item,
				'depth' => $depth,
				'order' => (int) $position++,
			];
			unset($items[$item]);

			if (!empty($children[$item])) {
				$tree += $this->buildLinearTree($children[$item], $items, $children, $depth+1);
			}
		}

		return $tree;
	}

}
