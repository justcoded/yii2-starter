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

abstract class ItemForm extends Model
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
			['name', 'uniqueItemName', 'on' => static::SCENARIO_CREATE],
			[['name', 'ruleName'], 'trim'],
			[['name', 'description', 'ruleName', 'data'], 'string'],
			[['type', 'createdAt', 'updatedAt'], 'integer'],
		];
	}

	/**
	 * Validate item (permission/role) name to be unique
	 *
	 * @param string $attribute
	 * @param array  $params
	 * @param mixed  $validator
	 *
	 * @return bool
	 */
	abstract public function uniqueItemName($attribute, $params, $validator);

	/**
	 * @inheritdoc
	 */
	public function attributeLabels()
	{
		return [
			'name' => 'Name',
			'description' => 'Description',
			'ruleName' => 'Rule Class',
		];
	}

	/**
	 * @inheritdoc
	 */
	public function attributeHints()
	{
		return [
			'ruleName' => 'This is the name of RBAC Rule class to be generated. 
					It should be a fully qualified namespaced class name, 
					e.g., <code>app\rbac\MyRule</code>',
		];
	}

	///=======================
	/// TODO: regactor below

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