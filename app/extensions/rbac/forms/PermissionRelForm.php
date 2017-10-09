<?php

namespace justcoded\yii2\rbac\forms;

use justcoded\yii2\rbac\models\Permission;
use Yii;
use yii\base\Model;

class PermissionRelForm extends Model
{
	const SCENARIO_ADDROLE = 'role';
	const SCENARIO_ADDPARENT = 'parent';
	const SCENARIO_ADDCHILD = 'child';

	/**
	 * @var string[]
	 */
	public $names;

	/**
	 * @var Permission
	 */
	protected $permission;

	/**
	 * @inheritdoc
	 * @return array
	 */
	public function rules()
	{
		return [
			['names', 'required'],
			['names', 'each', 'rule' => [
				'match', 'pattern' => ItemForm::getNamePattern(),
			]],
			['scenario', 'in',
			        'range' => static::getScenarios(),
			        'on' => static::getScenarios(),
			],
			['scenario', 'safe'],
		];
	}

	/**
	 * List of available scenarios
	 *
	 * @return array
	 */
	public static function getScenarios()
	{
		return [
			static::SCENARIO_ADDROLE,
			static::SCENARIO_ADDPARENT,
			static::SCENARIO_ADDCHILD,
		];
	}

	/**
	 * Setter for $permission
	 *
	 * @param Permission $permission
	 */
	public function setPermission(Permission $permission)
	{
		$this->permission = $permission;
	}

	/**
	 * ADD form process method
	 *
	 * @return bool|int
	 */
	public function addRelations()
	{
		if (! $this->validate()) {
			return false;
		}

		$added = 0;
		foreach ($this->names as $name) {
			list($parent, $child) = $this->getParentChild($name);

			if ($parent && $child && ! Yii::$app->authManager->hasChild($parent, $child)) {
				Yii::$app->authManager->addChild($parent, $child);
				$added++;
			}
		}
		return $added;
	}

	/**
	 * REMOVE form process method
	 *
	 * @param string $itemName
	 *
	 * @return bool
	 */
	public function removeRelation($itemName)
	{
		list($parent, $child) = $this->getParentChild($itemName);
		if (! $parent || ! $child || ! Yii::$app->authManager->hasChild($parent, $child)) {
			return false;
		}
		return Yii::$app->authManager->removeChild($parent, $child);
	}

	/**
	 * Helper to define correct parent and child
	 *
	 * @param string $itemName
	 *
	 * @return array
	 */
	public function getParentChild($itemName)
	{
		$item = (static::SCENARIO_ADDROLE === $this->scenario) ?
			Yii::$app->authManager->getRole($itemName) :
			Yii::$app->authManager->getPermission($itemName);

		$parent = (static::SCENARIO_ADDCHILD === $this->scenario)? $this->permission->getItem() : $item;
		$child = (static::SCENARIO_ADDCHILD === $this->scenario)? $item : $this->permission->getItem();

		return [$parent, $child];
	}
}
