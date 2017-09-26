<?php

namespace justcoded\yii2\rbac\controllers;

use justcoded\yii2\rbac\forms\PermissionForm;
use justcoded\yii2\rbac\forms\RoleForm;
use justcoded\yii2\rbac\models\AuthItemSearch;
use Yii;
use app\traits\controllers\FindModelOrFail;
use yii\filters\VerbFilter;
use vova07\console\ConsoleRunner;
use yii\web\Response;
use yii\widgets\ActiveForm;
use yii\web\Controller;

/**
 * PermissionsController implements the CRUD actions for AuthItems model.
 */
class RolesController extends Controller
{
	use FindModelOrFail;

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
	 * @return string|Response
	 */
	public function actionCreate()
	{
		$model = new RoleForm();
		$model->scenario = $model::SCENARIO_CREATE;

		if (Yii::$app->request->isAjax && $model->load(Yii::$app->request->post())) {

			Yii::$app->response->format = Response::FORMAT_JSON;
			return ActiveForm::validate($model);
		}

		if ($model->load(Yii::$app->request->post()) && $model->store()){
			Yii::$app->session->setFlash('success', 'Role saved success.');
			return $this->redirect(['index/index']);
		}


		return $this->render('create', [
			'model' => $model,
		]);

	}

	/**
	 * @param $name
	 * @return string
	 */
	public function actionUpdate($name)
	{
		$model = RoleForm::findOne($name);

		if (Yii::$app->request->isAjax && $model->load(Yii::$app->request->post())) {
			Yii::$app->response->format = Response::FORMAT_JSON;

			return ActiveForm::validate($model);
		}

		if ($model->load(Yii::$app->request->post()) && $model->store()){
			Yii::$app->session->setFlash('success', 'Role saved success.');

			return $this->redirect(['index/index']);
		}

		return $this->render('update', [
			'model' => $model
		]);
	}

	/**
	 * @param $name
	 * @return Response
	 */
	public function actionDelete($name)
	{
		if(!$post_data = Yii::$app->request->post('RoleForm')){
			return $this->redirect(['index/index']);
		}

		$role = Yii::$app->authManager->getRole($post_data['name']);

		if (Yii::$app->authManager->remove($role)){
			Yii::$app->session->setFlash('success', 'Role removed success.');
		}else{
			Yii::$app->session->setFlash('error', 'Role not removed.');
		}

		return $this->redirect(['index/index']);
	}
}

