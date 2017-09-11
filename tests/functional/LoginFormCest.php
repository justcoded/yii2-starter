<?php

class LoginFormCest
{
	public function _before(\FunctionalTester $I)
	{
		$I->amOnRoute('auth/login');
	}

	public function openLoginPage(\FunctionalTester $I)
	{
		$I->see('Login', 'h1');
	}

	// demonstrates `amLoggedInAs` method
	public function internalLoginById(\FunctionalTester $I)
	{
		$user = \app\models\User::findIdentity(1);

		$I->amLoggedInAs(1);
		$I->amOnPage('/');
		$I->see("Logout ($user->username)");
	}

	// demonstrates `amLoggedInAs` method
	public function internalLoginByInstance(\FunctionalTester $I)
	{
		$user = \app\models\User::findIdentity(1);
		$I->amLoggedInAs($user);
		$I->amOnPage('/');
		$I->see("Logout ($user->username)");
	}

	public function loginWithEmptyCredentials(\FunctionalTester $I)
	{
		$I->submitForm('#login-form', []);
		$I->expectTo('see validations errors');
		$I->see('Username cannot be blank.');
		$I->see('Password cannot be blank.');
	}

	public function loginWithWrongCredentials(\FunctionalTester $I)
	{
		$I->submitForm('#login-form', [
			'LoginForm[username]' => 'admin',
			'LoginForm[password]' => 'wrong',
		]);
		$I->expectTo('see validations errors');
		$I->see('Incorrect username or password.');
	}

	public function loginSuccessfully(\FunctionalTester $I)
	{
		$user = \app\models\User::findIdentity(1);

		$I->submitForm('#login-form', [
			'LoginForm[username]' => $user->username,
			'LoginForm[password]' => 'password_0',
		]);
		$I->see("Logout ($user->username)");
		$I->dontSeeElement('form#login-form');
	}
}