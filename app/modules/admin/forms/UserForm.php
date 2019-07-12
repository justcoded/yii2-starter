<?php

namespace app\modules\admin\forms;

use app\models\User;
use Yii;

class UserForm extends User
{
	/**
	 * @var string
	 */
	public $password;
	
	/**
	 * @var string
	 */
	public $passwordRepeat;
	
	/**
	 * @var string
	 */
	public $roles;
	
	/**
	 * @inheritdoc
	 * @return array
	 */
	public function rules()
	{
		return array_merge(parent::rules(), [
			[['password', 'passwordRepeat'], 'string'],
			[
				'passwordRepeat',
				'required',
				'when'       => function () {
					return !empty($this->password);
				},
				'whenClient' => 'function() { return $.trim($("#userform-password").val()).length || false; }',
			],
			['passwordRepeat', 'compare', 'compareAttribute' => 'password'],
			['roles', 'in', 'range' => array_keys(static::getRolesList()), 'allowArray' => true],
		]);
	}
	
	/**
	 * @inheritdoc
	 * @return bool
	 */
	public function beforeSave($insert)
	{
		if ($this->password) {
			$this->setPassword($this->password);
		}
		
		return parent::beforeSave($insert);
	}

	/**
	 * @inheritDoc
	 */
	public function afterSave($insert, $changedAttributes)
	{
		parent::afterSave($insert, $changedAttributes);

		$this->updateUserRoles($this->roles);
	}

	/**
	 * @inheritdoc
	 */
	public function afterFind()
	{
		$roles = array_keys(Yii::$app->authManager->getRolesByUser($this->id));
		$roles = array_combine($roles, $roles);
		$this->roles = $roles;
		
		parent::afterFind();
	}
	
	/**
	 * @param array|string $roles
	 */
	protected function updateUserRoles($roles)
	{
		if (empty($roles)) {
			return;
		}
		
		Yii::$app->authManager->revokeAll($this->id);
		
		foreach ($roles as $role) {
			$this->assignRole($role);
		}
	}
}