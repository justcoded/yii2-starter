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

	public $names;

	/**
	 * @var Permission
	 */
	protected $permission;

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

	public static function getScenarios()
	{
		return [
			static::SCENARIO_ADDROLE,
			static::SCENARIO_ADDPARENT,
			static::SCENARIO_ADDCHILD,
		];
	}

	public function setPermission($permission)
	{
		$this->permission = $permission;
	}

	public function addRelations()
	{
		if (! $this->validate()) {
			return false;
		}

		$added = 0;
		foreach ($this->names as $name) {
			$item = (static::SCENARIO_ADDROLE === $this->scenario) ?
				Yii::$app->authManager->getRole($name) :
				Yii::$app->authManager->getPermission($name);

			$parent = (static::SCENARIO_ADDCHILD === $this->scenario)? $this->permission->getItem() : $item;
			$child = (static::SCENARIO_ADDCHILD === $this->scenario)? $item : $this->permission->getItem();

			if ($item && ! Yii::$app->authManager->hasChild($parent, $child)) {
				Yii::$app->authManager->addChild($parent, $child);
				$added++;
			}
		}
		return $added;
	}

}
