<?php

namespace app\forms;

use app\models\User;
use Yii;
use yii\base\Model;
use yii\helpers\Url;

class PasswordRequestForm extends Model
{
	public $email;
	
	
	/**
	 * @return array the validation rules.
	 */
	public function rules()
	{
		return [
			['email', 'required'],
			['email', 'email'],
		];
	}
	
	/**
	 * Sends an email to the user with reset token if the email exists in the DB
	 *
	 * @return bool
	 */
	public function request()
	{
		if ($this->validate()) {
			$user = User::findByUsername($this->email);
			
			if (empty($user)) {
				return true; //needed for security reasons
			}
			
			$user->generatePasswordResetToken();
			$user->save();
			
			Yii::$app->mailer->compose()
				->setTo($user->email)
				->setFrom(settings()->app->systemFriendlyEmail)
				->setSubject('Restore your password on ' . Yii::$app->name)
				->setTextBody($this->getMessageBody($user->password_reset_token))
				->send();
			
			return true;
		}
		
		return false;
	}
	
	/**
	 * Render a message with reset token
	 *
	 * @param string $resetToken
	 *
	 * @return string
	 */
	protected function getMessageBody($resetToken)
	{
		return 'To restore your password, please, follow this link ' . Url::to([
				'auth/password-update',
				'token' => $resetToken,
			], true);
	}
}