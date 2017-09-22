<?php

namespace justcoded\yii2\settings\forms;

/**
 * Class AppSettingsForm
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
	public $senderEmail;
	
	/**
	 * Name, which will be set in 'from' mail column
	 *
	 * @var string
	 */
	public $senderName;
	
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
			[['adminEmail', 'adminName', 'senderName', 'senderEmail'], 'string'],
			[['adminEmail', 'senderEmail'], 'email'],
			[['passwordResetToken', 'rememberMeExpiration', 'senderEmail', 'adminEmail'], 'required'],
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
			'senderEmail' => 'Sender email',
			'senderName' => 'Sender name',
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
	public function getSenderFriendlyEmail()
	{
		return [$this->senderEmail => $this->senderName];
	}
}