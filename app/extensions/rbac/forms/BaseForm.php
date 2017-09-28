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

class BaseForm extends Model
{

	const SCENARIO_CREATE = 'create';

	public $name;
	public $type;
	public $description;
	public $rule_name;
	public $data;
	public $created_at;
	public $updated_at;

	/**
	 * @return array
	 */
	public function rules()
	{
		return  [
			[['type', 'name'], 'required'],
			[['name', 'description', 'rule_name', 'data'], 'string'],
			[['type', 'created_at', 'updated_at'], 'integer']
		];
	}

	/**
	 * @param $name
	 */
	public function setRole($name)
	{
		$this->role = Yii::$app->authManager->getRole($name);
	}

	/**
	 * @return null|\yii\rbac\Role
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
}