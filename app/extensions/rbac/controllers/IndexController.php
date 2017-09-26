<?php

namespace justcoded\yii2\rbac\controllers;

use justcoded\yii2\rbac\forms\PermissionForm;
use justcoded\yii2\rbac\forms\RoleForm;
use justcoded\yii2\rbac\models\AuthItemSearch;
use justcoded\yii2\rbac\models\Role;
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
class IndexController extends Controller
{
	use FindModelOrFail;

	/**
	 * @return string
	 */
	public function actionIndex()
	{
		$searchModelRoles  = new AuthItemSearch();
		$dataProviderRoles = $searchModelRoles->searchRoles(Yii::$app->request->queryParams);

		$dataProviderPermissions = $searchModelRoles->searchPermissions(Yii::$app->request->queryParams);

		return $this->render('/index', [
			'searchModelRoles'  => $searchModelRoles,
			'dataProviderRoles' => $dataProviderRoles,
			'dataProviderPermissions' => $dataProviderPermissions,
		]);
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
