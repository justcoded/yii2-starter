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
class PermissionsController extends Controller
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
					'delete-role' => ['POST'],
					'delete-permission' => ['POST'],
				],
			],
		];
	}

	/**
	 * @return string
	 */
	public function actionIndex()
	{

		$searchModelRoles  = new AuthItemSearch();
		$dataProviderRoles = $searchModelRoles->searchRoles(Yii::$app->request->queryParams);

		$dataProviderPermissions = $searchModelRoles->searchPermissions(Yii::$app->request->queryParams);

		return $this->render('index', [
			'searchModelRoles'  => $searchModelRoles,
			'dataProviderRoles' => $dataProviderRoles,
			'dataProviderPermissions' => $dataProviderPermissions,
		]);
	}

	/**
	 * @return string|Response
	 */
	public function actionAddRole()
	{
		$model = new RoleForm();
		$model->scenario = $model::SCENARIO_ADD;

		if (Yii::$app->request->isAjax && $model->load(Yii::$app->request->post())) {

			Yii::$app->response->format = Response::FORMAT_JSON;
			return ActiveForm::validate($model);
		}

		if ($model->load(Yii::$app->request->post()) && $model->store()){
			Yii::$app->session->setFlash('success', 'Role saved success.');
			return $this->redirect(['index']);
		}


		return $this->render('create-role', [
			'model' => $model,
		]);

	}

	/**
	 * @param $name
	 * @return string
	 */
	public function actionUpdateRole($name)
	{
		$model = RoleForm::findOne($name);

		if (Yii::$app->request->isAjax && $model->load(Yii::$app->request->post())) {
			Yii::$app->response->format = Response::FORMAT_JSON;

			return ActiveForm::validate($model);
		}

		if ($model->load(Yii::$app->request->post()) && $model->store()){
			Yii::$app->session->setFlash('success', 'Role saved success.');

			return $this->redirect(['index']);
		}

		return $this->render('update-role', [
			'model' => $model
		]);
	}


	/**
	 * @param $name
	 * @return Response
	 */
	public function actionDeleteRole($name)
	{
		if(!$post_data = Yii::$app->request->post('RoleForm')){
			return $this->redirect(['index']);
		}

		$role = Yii::$app->authManager->getRole($post_data['name']);

		if (Yii::$app->authManager->remove($role)){
			Yii::$app->session->setFlash('success', 'Role removed success.');
		}else{
			Yii::$app->session->setFlash('error', 'Role not removed.');
		}

		return $this->redirect(['index']);
	}

	/**
	 * @return string
	 */
	public function actionAddPermission()
	{
		$model = new PermissionForm();
		$model->scenario = $model::SCENARIO_ADD;

		if (Yii::$app->request->isAjax && $model->load(Yii::$app->request->post())) {
			Yii::$app->response->format = Response::FORMAT_JSON;

			return ActiveForm::validate($model);
		}

		if ($model->load(Yii::$app->request->post()) && $model->store()){
			Yii::$app->session->setFlash('success', 'Permissions saved success.');

			return $this->redirect(['index']);
		}

		return $this->render('create-permission', [
			'model' => $model
		]);
	}

	/**
	 * @param $name
	 * @return string
	 */
	public function actionUpdatePermission($name)
	{
		$model = PermissionForm::findOne($name);

		if (Yii::$app->request->isAjax && $model->load(Yii::$app->request->post())) {
			Yii::$app->response->format = Response::FORMAT_JSON;

			return ActiveForm::validate($model);
		}

		if ($model->load(Yii::$app->request->post()) && $model->store()){
			Yii::$app->session->setFlash('success', 'Permissions saved success.');

			return $this->redirect(['index']);
		}

		return $this->render('update-permission', [
			'model' => $model
		]);
	}

	/**
	 * @param $name
	 * @return Response
	 */
	public function actionDeletePermission($name)
	{
		if(!$post_data = Yii::$app->request->post('PermissionForm')){
			return $this->redirect(['index']);
		}

		$role = Yii::$app->authManager->getPermission($post_data['name']);

		if (Yii::$app->authManager->remove($role)){
			Yii::$app->session->setFlash('success', 'Permission removed success.');
		}else{
			Yii::$app->session->setFlash('error', 'Permission not removed.');
		}

		return $this->redirect(['index']);
	}

	/**
	 * @return \yii\web\Response
	 */
	public function actionScanRoutes()
	{
		$cr = new ConsoleRunner(['file' => '@app/../yii']);
		if($cr->run('rbac/scan')) {
			Yii::$app->session->setFlash('success', 'Routes scanned success.');
		}

		return $this->redirect(['index']);
	}

}
