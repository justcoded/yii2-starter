<?php

namespace justcoded\yii2\rbac\controllers;

use justcoded\yii2\rbac\models\ItemSearch;
use Yii;
use app\traits\controllers\FindModelOrFail;
use vova07\console\ConsoleRunner;
use yii\web\Controller;


class IndexController extends Controller
{
	use FindModelOrFail;

	/**
	 * @return string
	 */
	public function actionIndex()
	{
		$searchModel = new ItemSearch();
		$dataProviderRoles = $searchModel->searchRoles(Yii::$app->request->queryParams);

		$dataProviderPermissions = $searchModel->searchPermissions(Yii::$app->request->queryParams);

		return $this->render('/index', [
			'searchModel' => $searchModel,
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
