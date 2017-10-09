<?php

namespace justcoded\yii2\rbac\controllers;

use justcoded\yii2\rbac\forms\PermissionForm;
use justcoded\yii2\rbac\forms\PermissionRelForm;
use justcoded\yii2\rbac\models\Permission;
use Yii;
use yii\filters\VerbFilter;
use yii\web\NotFoundHttpException;
use yii\web\Response;
use yii\web\Controller;
use justcoded\yii2\rbac\models\ItemSearch;
use justcoded\yii2\rbac\forms\ScanForm;

/**
 * PermissionsController implements the CRUD actions for AuthItems model.
 */
class PermissionsController extends Controller
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
					'remove-relation' => ['POST'],
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

		if ($model->load(Yii::$app->request->post()) && $model->save()) {
			Yii::$app->session->setFlash('success', 'Permission saved successfully.');

			return $this->redirect(['update', 'name' => $model->name]);
		}

		return $this->render('create', [
			'model' => $model
		]);
	}

	/**
	 * @param string $name
	 *
	 * @return array|string|Response
	 * @throws NotFoundHttpException
	 */
	public function actionUpdate($name)
	{
		if (! $perm = Permission::find($name)) {
			throw new NotFoundHttpException('The requested page does not exist.');
		}

		$model = new PermissionForm();
		$model->setPermission($perm);

		if ($model->load(Yii::$app->request->post()) && $model->save()) {
			Yii::$app->session->setFlash('success', 'Permission saved successfully.');

			return $this->redirect(['update', 'name' => $model->name]);
		}

		$relModel = new PermissionRelForm();
		return $this->render('update', [
			'model' => $model,
			'permission' => $perm,
			'relModel' => $relModel,
		]);
	}

	/**
	 * Delete a permission
	 *
	 * @param string $name
	 *
	 * @return Response
	 * @throws NotFoundHttpException
	 */
	public function actionDelete($name)
	{
		if (! $perm = Permission::find($name)) {
			throw new NotFoundHttpException('The requested page does not exist.');
		}

		if (Yii::$app->authManager->remove($perm->getItem())) {
			Yii::$app->session->setFlash('success', 'Permission removed successfully.');
		}

		return $this->redirect(['index']);
	}

	/**
	 * Add relations to a permission
	 *
	 * @param string $name
	 *
	 * @return Response
	 * @throws NotFoundHttpException
	 */
	public function actionAddRelation($name)
	{
		if (! $perm = Permission::find($name)) {
			throw new NotFoundHttpException('The requested page does not exist.');
		}

		$model = new PermissionRelForm();
		if ($model->load(Yii::$app->request->post())) {
			$model->setPermission($perm);
			if ($model->addRelations()) {
				Yii::$app->session->setFlash('success', 'New relations added successfully.');
			} else {
				$errors = $model->getFirstErrors();
				Yii::$app->session->setFlash('warning', $errors ? reset($errors) : 'Some error occured.');
			}
		}

		return $this->redirect(['update', 'name' => $name]);
	}

	/**
	 * Remove relation from permission
	 *
	 * @param string $name
	 * @param string $item
	 * @param string $scenario
	 *
	 * @return Response
	 * @throws NotFoundHttpException
	 */
	public function actionRemoveRelation($name, $item, $scenario)
	{
		if (! $perm = Permission::find($name)) {
			throw new NotFoundHttpException('The requested page does not exist.');
		}

		$model = new PermissionRelForm();
		$model->setScenario($scenario);
		$model->setPermission($perm);

		if ($model->removeRelation($item)) {
			Yii::$app->session->setFlash('success', 'Relations removed.');
		} else {
			Yii::$app->session->setFlash('warning', 'Some error occured.');
		}

		return $this->redirect(['update', 'name' => $name]);
	}


	// TODO: check scan form
	/**
	 * @return string|Response
	 */
	public function actionScan()
	{
		$model = new ScanForm();

		if ($model->load(Yii::$app->request->post()) && $model->validate()) {
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

