<?php

namespace justcoded\yii2\rbac\controllers;

use justcoded\yii2\rbac\forms\RoleForm;
use justcoded\yii2\rbac\models\Role;
use Yii;
use yii\filters\VerbFilter;
use yii\web\NotFoundHttpException;
use yii\web\Response;
use yii\web\Controller;

/**
 * RolesController implements the CRUD actions for AuthItems model.
 */
class RolesController extends Controller
{
	/**
	 * @inheritdoc
	 */
	public function behaviors()
	{
		return [
			'verbs' => [
				'class'   => VerbFilter::className(),
				'actions' => [
					'delete' => ['POST'],
				],
			],
		];
	}

	/**
	 * Create form/action
	 *
	 * @return array|string|Response
	 */
	public function actionCreate()
	{
		$model = new RoleForm();
		$model->scenario = $model::SCENARIO_CREATE;

		if ($model->load(Yii::$app->request->post()) && $model->save()) {
			Yii::$app->session->setFlash('success', 'Role saved successfully.');

			return $this->redirect(['update', 'name' => $model->name]);
		}

		return $this->render('create', [
			'model' => $model,
		]);
	}

	/**
	 * Update form/action
	 *
	 * @param string $name
	 *
	 * @return array|string|Response
	 * @throws NotFoundHttpException
	 */
	public function actionUpdate($name)
	{
		if (! $role = Role::find($name)) {
			throw new NotFoundHttpException('The requested page does not exist.');
		}

		$model = new RoleForm();
		$model->setRole($role);

		if ($model->load(Yii::$app->request->post()) && $model->save()) {
			Yii::$app->session->setFlash('success', 'Role saved successfully.');

			return $this->redirect(['update', 'name' => $model->name]);
		}

		return $this->render('update', [
			'model' => $model,
			'role'  => $role,
		]);
	}

	/**
	 * Delete action
	 *
	 * @param string $name
	 *
	 * @return Response
	 * @throws NotFoundHttpException
	 */
	public function actionDelete($name)
	{
		if (! $role = Role::find($name)) {
			throw new NotFoundHttpException('The requested page does not exist.');
		}

		if (Yii::$app->authManager->remove($role->getItem())) {
			Yii::$app->session->setFlash('success', 'Role removed successfully.');
		}

		return $this->redirect(['permissions/index']);
	}
}

