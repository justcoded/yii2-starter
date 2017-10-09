<?php

namespace justcoded\yii2\rbac\controllers;


use justcoded\yii2\rbac\forms\ScanForm;
use Yii;
use app\traits\controllers\FindModelOrFail;
use yii\filters\VerbFilter;
use yii\web\Controller;


/**
 * ScanController implements the CRUD actions for AuthItems model.
 */
class ScanController extends Controller
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
		$model = new ScanForm();

		if($model->load(Yii::$app->request->post()) && $model->validate()){
			if ($model->scan()) {
				Yii::$app->session->setFlash('success', 'Routes scaned success.');
			}

			return $this->redirect(['permissions/']);
		}

		return $this->render('index', [
			'model' => $model,
		]);
	}
}

