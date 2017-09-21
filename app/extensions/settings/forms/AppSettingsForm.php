<?php

namespace app\extensions\settings\forms;

use app\extensions\settings\forms\SettingsForm;

class AppSettingsForm extends SettingsForm
{
	public $adminEmail;
	
	public $adminName;
	
	public $senderEmail;
	
	public $senderName;
	
	public $passwordResetToken;
	
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
	 * @return string
	 */
	public function sectionName()
	{
		return 'app';
	}
	
	/**
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