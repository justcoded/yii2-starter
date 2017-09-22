<?php

namespace app\modules\admin\controllers;

use app\models\AuthItems;
use app\modules\admin\forms\PermissionForm;
use app\modules\admin\forms\RoleForm;
use app\modules\admin\models\AuthItemSearch;
use Yii;
use app\traits\controllers\FindModelOrFail;
use yii\filters\VerbFilter;
use vova07\console\ConsoleRunner;
use yii\web\Response;
use yii\widgets\ActiveForm;

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
		$model->scenario = 'add';

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

		$model->scenario = 'update';
		return $this->render('update-role', [
			'model' => $model
		]);
	}

	/**
	 * @return Response
	 */
	public function actionStoreRoles()
	{
		$form = Yii::$app->request->post('RoleForm');

		if(!$model = RoleForm::findOne($form['name'])) {

			$model = new RoleForm();
		}

		if ($model->load(Yii::$app->request->post()) && $model->validate()){

			if ($model->store()){
				Yii::$app->session->setFlash('success', 'Role saved success.');
			}
		}

		return $this->redirect(['index']);
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
	 * @return Response
	 */
	public function actionStorePermissions()
	{
		$form = Yii::$app->request->post('PermissionForm');

		if(!$model = PermissionForm::findOne($form['name'])) {

			$model = new PermissionForm();
		}

		if ($model->load(Yii::$app->request->post())){

			if (!$model->validate()){
				$errors = $model->errors;
				Yii::$app->session->setFlash('success', $errors);
				return $this->redirect('add-permission');
			}

			if ($model->store()){
				Yii::$app->session->setFlash('success', 'Permissions saved success.');
			}

		}

		return $this->redirect(['index']);
	}

	/**
	 * @return array
	 */
	public function actionValidatePermission() {

		$model = new PermissionForm();

		if (Yii::$app->request->isAjax && $model->load(Yii::$app->request->post())) {
			Yii::$app->response->format = Response::FORMAT_JSON;
			return ActiveForm::validate($model);
		}
	}

	/**
	 * @return array
	 */
	public function actionValidateRole() {

		$model = new RoleForm();

		if (Yii::$app->request->isAjax && $model->load(Yii::$app->request->post())) {
			Yii::$app->response->format = Response::FORMAT_JSON;
			return ActiveForm::validate($model);
		}
	}

	/**
	 * @return \yii\web\Response
	 */
	public function actionScanRoutes()
	{
		$cr = new ConsoleRunner(['file' => '@yii/yii']);
		$cr->run('rbac/scan');
		Yii::$app->session->setFlash('success', 'Routes scanned success.');

		return $this->redirect(['index']);
	}

}
