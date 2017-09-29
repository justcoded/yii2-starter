<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 28/09/17
 * Time: 09:28
 */

namespace justcoded\yii2\rbac\forms;

use yii\base\Model;
use Yii;
use yii\helpers\ArrayHelper;

class ItemForm extends Model
{

	const SCENARIO_CREATE = 'create';

	public $name;
	public $type;
	public $description;
	public $ruleName;
	public $data;
	public $createdAt;
	public $updatedAt;

	/**
	 * @return array
	 */
	public function rules()
	{
		return  [
			[['type', 'name'], 'required'],
			[['name', 'description', 'ruleName', 'data'], 'string'],
			[['type', 'createdAt', 'updatedAt'], 'integer'],
			['name', 'uniqueName', 'on' => static::SCENARIO_CREATE],
		];
	}


	/**
	 * @return array|bool
	 */
	public static function getRolesList()
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
	 * @return array
	 */
	public static function getDropDownWithRoles()
	{
		$roles = static::getRolesList();
		return ArrayHelper::merge(['' => 'All'], $roles);
	}
}