<?php

namespace tests\models;

use app\forms\LoginForm;
use Codeception\Specify;
use app\fixtures\UserFixture;

class LoginFormTest extends \Codeception\Test\Unit
{
	/**
	 * @var \UnitTester
	 */
	protected $tester;

	/**
	 * @var LoginForm
	 */
	private $model;

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

	protected function _after()
	{
		\Yii::$app->user->logout();
	}

	public function testLoginNoUser()
	{
		$this->model = new LoginForm([
			'username' => 'not_existing_username',
			'password' => 'not_existing_password',
		]);

		expect_not($this->model->login());
		expect_that(\Yii::$app->user->isGuest);
	}

	public function testLoginWrongPassword()
	{
		$this->model = new LoginForm([
			'username' => $this->validUser['username'],
			'password' => 'wrong_password',
		]);

		expect_not($this->model->login());
		expect_that(\Yii::$app->user->isGuest);
		expect($this->model->errors)->hasKey('password');
	}

	public function testLoginCorrect()
	{
		$this->model = new LoginForm([
			'username' => $this->validUser['username'],
			'password' => $this->validUser['password'],
		]);

		expect_that($this->model->login());
		expect_not(\Yii::$app->user->isGuest);
		expect($this->model->errors)->hasntKey('password');
	}

}
