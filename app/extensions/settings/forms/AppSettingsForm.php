<?php

namespace justcoded\yii2\settings\forms;

/**
 * Class AppSettingsForm
 *
 * @property array adminFriendlyEmail
 * @property array systemFriendlyEmail
 *
 * @package justcoded\yii2\settings\forms
 */
class AppSettingsForm extends SettingsForm
{
	/**
	 * Email where to send letters to admin
	 *
	 * @var string
	 */
	public $adminEmail;
	
	/**
	 * Admin name
	 *
	 * @var string
	 */
	public $adminName;
	
	/**
	 * Email, letters to users will come from
	 *
	 * @var string
	 */
	public $systemEmail;
	
	/**
	 * Name, which will be set in 'from' mail column
	 *
	 * @var string
	 */
	public $systemName;
	
	/**
	 * In minuts
	 *
	 * @var integer
	 */
	public $passwordResetToken;
	
	/**
	 * In days
	 *
	 * @var integer
	 */
	public $rememberMeExpiration;
	
	/**
	 * @inheritdoc
	 */
	public function rules()
	{
		return [
			[['adminEmail', 'adminName', 'systemEmail', 'systemName'], 'string'],
			[['adminEmail', 'systemEmail'], 'email'],
			[['passwordResetToken', 'rememberMeExpiration', 'systemEmail', 'adminEmail'], 'required'],
			[['passwordResetToken', 'rememberMeExpiration'], 'integer'],
		];
	}
	
	/**
	 * @inheritdoc
	 */
	public function attributeLabels()
	{
		return [
			'adminEmail' => 'Admin email',
			'adminName' => 'Admin name',
			'systemEmail' => 'System email',
			'systemName' => 'System email name',
			'passwordResetToken' => 'Password reset token',
			'rememberMeExpiration' => 'Remember me expiration',
		];
	}
	
	/**
	 * @inheritdoc
	 */
	public function sectionName()
	{
		return 'app';
	}
	
	/**
	 * Get full admin data to set in mailer from method
	 *
	 * @return array
	 */
	public function getAdminFriendlyEmail()
	{
		return [$this->adminEmail => $this->adminName];
	}
	
	/**
	 * @return array
	 */
	public function getSystemFriendlyEmail()
	{
		return [$this->systemEmail => $this->systemName];
	}
}