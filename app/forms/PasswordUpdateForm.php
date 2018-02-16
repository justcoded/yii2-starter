<?php

namespace app\forms;

use app\models\User;
use yii\base\Model;

class PasswordUpdateForm extends Model
{
	public $resetToken;
	public $newPassword;
	public $newPasswordRepeat;
	
	
	/**
	 * @return array the validation rules.
	 */
	public function rules()
	{
		return [
			[['newPassword', 'newPasswordRepeat'], 'required'],
			['newPasswordRepeat', 'compare', 'compareAttribute' => 'newPassword'],
		];
	}
	
	/**
	 * Updates user's password if $resetToken is valid
	 *
	 * @return bool
	 */
	public function update()
	{
		$user = User::findByPasswordResetToken($this->resetToken);
		
		if (empty($user)) {
			return false;
		}
		
		if ($this->validate()) {
			$user->setPassword($this->newPassword);
			$user->removePasswordResetToken();
			
			if (!$user->save()) {
				$this->addErrors($user->errors);
				
				return false;
			}
			
			return true;
		}
		
		return false;
	}
}