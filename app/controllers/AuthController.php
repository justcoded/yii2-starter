<?php

namespace app\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\web\NotFoundHttpException;
use yii\web\Response;
use yii\filters\VerbFilter;
use app\forms\LoginForm;
use app\forms\PasswordRequestForm;
use app\forms\PasswordUpdateForm;
use app\forms\RegisterForm;
use app\models\User;

class AuthController extends Controller
{
	/**
	 * @inheritdoc
	 */
	public function behaviors()
	{
		return [
			'access' => [
				'class' => AccessControl::class,
				'only'  => ['logout'],
				'rules' => [
					[
						'actions' => ['logout'],
						'allow'   => true,
						'roles'   => ['@'],
					],
				],
			],
			'verbs'  => [
				'class'   => VerbFilter::class,
				'actions' => [
					'logout' => ['post'],
				],
			],
		];
	}

	/**
	 * Login action.
	 *
	 * @return Response|string
	 */
	public function actionLogin()
	{
		if (! Yii::$app->user->isGuest) {
			return $this->goHome();
		}

		$model = new LoginForm();
		if ($model->load(Yii::$app->request->post()) && $model->login()) {
			$model->assignAuthenticatedRole();
			return $this->goBack();
		}

		return $this->render('login', [
			'model' => $model,
		]);
	}

	/**
	 * Logout action.
	 *
	 * @return Response
	 */
	public function actionLogout()
	{
		Yii::$app->user->logout();

		return $this->goHome();
	}

	/**
	 * Register action.
	 *
	 * @return string|Response
	 */
	public function actionRegister()
	{
		if (!Yii::$app->user->isGuest) {
			return $this->goHome();
		}

		$model = new RegisterForm();

		if ($model->load(Yii::$app->request->post()) && $model->register()) {
			Yii::$app->session->addFlash('success', 'You have been successfully registered');

			return $this->goBack();
		}

		return $this->render('register', [
			'model' => $model,
		]);
	}

	/**
	 * Password request action
	 *
	 * @return string|Response
	 */
	public function actionPasswordRequest()
	{
		if (!Yii::$app->user->isGuest) {
			return $this->goHome();
		}

		$model = new PasswordRequestForm();

		if ($model->load(Yii::$app->request->post()) && $model->request()) {
			Yii::$app->session->addFlash(
				'success',
				'If the email address is registered in the system, we would send a letter there shortly.'
			);

			return $this->goBack();
		}

		return $this->render('password-request', [
			'model' => $model,
		]);
	}

	/**
	 * Password update action
	 *
	 * @param string $token Password reset token.
	 *
	 * @return string|Response
	 * @throws NotFoundHttpException If token not found in DB.
	 */
	public function actionPasswordUpdate($token)
	{
		if (!Yii::$app->user->isGuest) {
			return $this->goHome();
		}

		if (!User::isPasswordResetTokenValid($token)) {
			throw new NotFoundHttpException('Page not found.');
		}

		$model = new PasswordUpdateForm();
		$model->resetToken = $token;

		if ($model->load(Yii::$app->request->post()) && $model->update()) {
			Yii::$app->session->addFlash('success', 'Your password has been successfully updated!');

			return $this->goBack();
		}

		return $this->render('password-update', [
			'model' => $model,
		]);
	}

}
