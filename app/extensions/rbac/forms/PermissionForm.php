<?php

namespace justcoded\yii2\rbac\forms;

use justcoded\yii2\rbac\models\Item;
use justcoded\yii2\rbac\models\Permission;
use yii\rbac\Role as RbacRole;
use yii\rbac\Permission as RbacPermission;
use Yii;
use yii\helpers\ArrayHelper;


class PermissionForm extends ItemForm
{
	/**
	 * @var string
	 */
	public $ruleClass;

	/**
	 * @var Permission
	 */
	protected $permission;

	/**
	 * @inheritdoc
	 */
	public function init()
	{
		$this->type = RbacPermission::TYPE_PERMISSION;
		$this->permission = new Permission();
	}

	/**
	 * @inheritdoc
	 * @return array
	 */
	public function rules()
	{
		return ArrayHelper::merge(parent::rules(), [
			['ruleClass', 'match', 'pattern' => '/^[a-z][\w\d\_\\\]*$/i'],
			['ruleClass', 'validRuleClass', 'skipOnEmpty' => true],
		]);
	}

	/**
	 * @inheritdoc
	 */
	public function attributeHints()
	{
		return [
			'ruleClass' => 'This is the name of RBAC Rule class to be generated. 
					It should be a fully qualified namespaced class name, 
					e.g., <code>app\rbac\MyRule</code>',
		];
	}

	/**
	 * @inheritdoc
	 */
	public function uniqueItemName($attribute, $params, $validator)
	{
		$permission = Permission::getList();
		return ! isset($permission[$this->$attribute]);
	}

	/**
	 * Validate Rule Class to be namespaced class name and instance of yii\rbac\Rule
	 *
	 * @param string $attribute
	 * @param array  $params
	 * @param mixed  $validator
	 *
	 * @return bool
	 */
	public function validRuleClass($attribute, $params, $validator)
	{
		$class = $this->$attribute;
		if (! class_exists($class)) {
			$this->addError($attribute, 'Not valid class name.');
			return false;
		} else {
			$reflect = new \ReflectionClass($class);
			if (! $reflect->isSubclassOf(\yii\rbac\Rule::className())) {
				$this->addError($attribute, 'Class have to be extended of \\yii\\rbac\\Rule class');
				return false;
			}
		}

		return true;
	}

	/**
	 * Load permission data to properties and set correct ruleClass
	 *
	 * @param Permission $permission
	 */
	public function setPermission(Permission $permission)
	{
		$this->permission = $permission;
		$this->load((array)$permission->getItem(), '');
		$this->ruleClass = $this->getRuleClassName();
	}

	/**
	 * Create single permission with rule name
	 *
	 * @return bool
	 */
	public function save()
	{
		if (! $this->validate()) {
			return false;
		}

		if (! $item = $this->permission->getItem()) {
			$item = Permission::create($this->name, $this->description);
		}

		$item->description = $this->description;
		if ($this->ruleClass) {
			$rule = $this->getRule($this->ruleClass);
			$item->ruleName = $rule->name;
		}


		return Yii::$app->authManager->update($item->name, $item);
	}

	/**
	 * Get RBAC Rule object
	 * create/register in case rule doesn't exists
	 *
	 * @param string $className
	 *
	 * @return object|\yii\rbac\Rule
	 */
	public function getRule($className)
	{
		$rules = Yii::$app->authManager->getRules();
		foreach ($rules as $rule) {
			if (get_class($rule) === $className) {
				return $rule;
			}
		}

		// no rule found - creating rule
		$rule = Yii::createObject("\\" . $className);
		Yii::$app->authManager->add($rule);
		return $rule;
	}

	/**
	 * Find rule namespaced class name by current ruleName
	 *
	 * @return null|string
	 */
	public function getRuleClassName()
	{
		if ($this->ruleName) {
			$rule = Yii::$app->authManager->getRule($this->ruleName);
			return get_class($rule);
		}
		return null;
	}

}