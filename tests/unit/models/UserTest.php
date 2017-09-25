<?php

namespace tests\models;

use app\fixtures\UserFixture;
use app\models\User;

class UserTest extends \Codeception\Test\Unit
{
	/**
	 * @var \UnitTester
	 */
	protected $tester;

	/**
	 * @var array
	 */
	private $validUser;

	public function _before()
	{
		$this->tester->haveFixtures([
			'user' => UserFixture::className(),
		]);
		$this->validUser = reset($this->tester->grabFixture('user')->data);
	}

	public function testFindUserById()
	{
		expect_that($user = User::findIdentity($this->validUser['id']));
		expect($user->id)->equals(1);

		expect_not(User::findIdentity(0));
	}

	public function testFindUserByUsername()
	{
		expect_that($user = User::findByUsername($this->validUser['username']));
		expect_not(User::findByUsername('randomUsernameHere'));
	}

	/**
	 * @depends testFindUserByUsername
	 */
	public function testValidateUser($user)
	{
		$user = User::findIdentity($this->validUser['id']);

		expect_that($user->validatePassword($this->validUser['password']));
		expect_not($user->validatePassword('not valid password'));
	}

}
