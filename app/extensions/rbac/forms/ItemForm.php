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
	 * @return array
	 */
	public static function getRolesList()
	{
		$data = Yii::$app->authManager->getRoles();

		return ArrayHelper::map($data, 'name', 'name');
	}


	/**
	 * @return array
	 */
	public function getPermissionsList()
	{
		$data = Yii::$app->authManager->getPermissions();

		return ArrayHelper::map($data, 'name', 'name');
	}


	/**
	 * @param array $array_parent
	 * @param array $params
	 * @param bool $permission
	 * @return bool
	 */
	public function addChildrenArray(array $array_parent, array $params, $permission = true)
	{
		foreach ($array_parent as $name) {

			if ($permission) {
				$item = Yii::$app->authManager->getPermission($name);
			}else{
				$item = Yii::$app->authManager->getRole($name);
			}

			if (isset($params['child'])) {
				Yii::$app->authManager->addChild($item, $params['child']);
			}elseif (isset($params['parent'])){
				Yii::$app->authManager->addChild($params['parent'], $item);
			}else{
				return false;
			}
		}

		return true;
	}

	/**
	 * @param array $array_parent
	 * @param $child
	 * @return bool
	 */
	public function removeChildrenArray(array $array_parent, $child)
	{
		foreach ($array_parent as $parent){
			Yii::$app->authManager->removeChild($parent, $child);
		}

		return true;
	}
}