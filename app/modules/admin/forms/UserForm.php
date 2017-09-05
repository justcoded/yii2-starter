<?php

namespace app\modules\admin\forms;

use app\models\User;

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
	 * @inheritdoc
	 * @return array
	 */
	public function rules()
	{
		return array_merge(parent::rules(), [
			['password', 'compare'],
			['password_repeat', 'safe'],
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
}