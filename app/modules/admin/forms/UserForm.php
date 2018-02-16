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
	public $password_repeat;
	
	/**
	 * @var string
	 */
	public $role;
	
	/**
	 * @inheritdoc
	 * @return array
	 */
	public function rules()
	{
		return array_merge(parent::rules(), [
			['password', 'safe'],
			['password_repeat', 'compare', 'compareAttribute' => 'password'],
			['role', 'in', 'range' => array_keys(static::getRolesList())],
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
		
		if ($this->role) {
			$this->assignRole($this->role);
		}
		
		return parent::beforeSave($insert);
	}
}