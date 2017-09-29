<?php

namespace justcoded\yii2\rbac\controllers;

use justcoded\yii2\rbac\models\ItemSearch;
use Yii;
use app\traits\controllers\FindModelOrFail;
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
		$file = '@app/../yii';
		$action = 'rbac/scan';

		$cmd = PHP_BINDIR . '/php ' . Yii::getAlias($file) . ' ' . $action;
		if ($this->isWindows() === true) {
			pclose(popen('start /b ' . $cmd, 'r'));
		} else {
			pclose(popen($cmd . ' > /dev/null &', 'r'));
		}

		Yii::$app->session->setFlash('success', 'Routes scanned success.');

		return $this->redirect(['index']);
	}

	/**
	 * Check operating system
	 *
	 * @return boolean true if it's Windows OS
	 */
	protected function isWindows()
	{
		if (PHP_OS == 'WINNT' || PHP_OS == 'WIN32') {
			return true;
		} else {
			return false;
		}
	}

}
