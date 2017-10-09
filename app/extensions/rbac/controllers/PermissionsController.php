<?php

namespace justcoded\yii2\rbac\controllers;

use justcoded\yii2\rbac\forms\PermissionForm;
use Yii;
use app\traits\controllers\FindModelOrFail;
use yii\filters\VerbFilter;
use yii\web\Response;
use yii\widgets\ActiveForm;
use yii\web\Controller;
use justcoded\yii2\rbac\models\ItemSearch;
use justcoded\yii2\rbac\forms\ScanForm;

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
					'delete' => ['POST'],
				],
			],
		];
	}

	/**
	 * @return string
	 */
	public function actionIndex()
	{
		$searchModel = new ItemSearch();
		$dataProviderRoles = $searchModel->searchRoles(Yii::$app->request->queryParams);

		$dataProviderPermissions = $searchModel->searchPermissions(Yii::$app->request->queryParams);

		return $this->render('index', [
			'searchModel' => $searchModel,
			'dataProviderRoles' => $dataProviderRoles,
			'dataProviderPermissions' => $dataProviderPermissions,
		]);
	}

	/**
	 * @return array|string|Response
	 */
	public function actionCreate()
	{
		$model = new PermissionForm();
		$model->scenario = $model::SCENARIO_CREATE;

		if (Yii::$app->request->isAjax && $model->load(Yii::$app->request->post())) {
			Yii::$app->response->format = Response::FORMAT_JSON;

			return ActiveForm::validate($model);
		}

		if($model->load(Yii::$app->request->post())){
			if ($model->store()) {
				Yii::$app->session->setFlash('success', 'Permission saved success.');
			}

			return $this->redirect(['index']);
		}

		return $this->render('create', [
			'model' => $model
		]);
	}

	/**
	 * @param $name
	 * @return array|string|Response
	 */
	public function actionUpdate($name)
	{
		$perm = Yii::$app->authManager->getPermission($name);
		$model = new PermissionForm($perm);

		if (Yii::$app->request->isAjax && $model->load(Yii::$app->request->post())) {
			Yii::$app->response->format = Response::FORMAT_JSON;
			return ActiveForm::validate($model);
		}

		if($model->load(Yii::$app->request->post())){
			if ($model->store()) {
				Yii::$app->session->setFlash('success', 'Permission saved success.');
			}

			return $this->redirect(['index']);
		}

		return $this->render('update', [
			'model' => $model
		]);
	}

	/**
	 * @return Response
	 */
	public function actionDelete()
	{
		if(!$post_data = Yii::$app->request->post('PermissionForm')){
			return $this->redirect(['index']);
		}

		$role = Yii::$app->authManager->getPermission($post_data['name']);

		if (Yii::$app->authManager->remove($role)){
			Yii::$app->session->setFlash('success', 'Permission removed success.');
		}

		return $this->redirect(['index']);
	}

	/**
	 * @return string|Response
	 */
	public function actionScan()
	{
		$model = new ScanForm();

		if($model->load(Yii::$app->request->post()) && $model->validate()){
			if ($model->scan()) {
				Yii::$app->session->setFlash('success', 'Routes scanned success.');
			}

			return $this->redirect(['index']);
		}

		return $this->render('scan', [
			'model' => $model,
		]);
	}
}

