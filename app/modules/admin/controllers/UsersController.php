<?php

namespace app\modules\admin\controllers;

use app\models\User;
use app\modules\admin\forms\UserForm;
use app\modules\admin\models\UserSearch;
use app\traits\controllers\FindModelOrFail;
use Yii;
use yii\filters\VerbFilter;

/**
 * UsersController implements the CRUD actions for User model.
 */
class UsersController extends Controller
{
	use FindModelOrFail;
	
	/**
	 * @inheritdoc
	 */
	public function init()
	{
		parent::init();
		$this->modelClass = UserForm::class;
	}
	
	/**
	 * @inheritdoc
	 */
	public function behaviors()
	{
		return [
			'verbs' => [
				'class'   => VerbFilter::class,
				'actions' => [
					'delete' => ['POST'],
				],
			],
		];
	}
	
	/**
	 * Lists all User models.
	 *
	 * @return mixed
	 */
	public function actionIndex()
	{
		$searchModel = new UserSearch();
		$dataProvider = $searchModel->search(Yii::$app->request->queryParams);
		
		return $this->render('index', [
			'searchModel'  => $searchModel,
			'dataProvider' => $dataProvider,
		]);
	}
	
	/**
	 * Creates a new User model.
	 * If creation is successful, the browser will be redirected to the 'view' page.
	 *
	 * @return mixed
	 */
	public function actionCreate()
	{
		$model = new UserForm();
		
		$model->on(User::EVENT_BEFORE_INSERT, [$model, 'generateAuthKey']);
		
		if ($model->load(Yii::$app->request->post()) && $model->save()) {
			return $this->redirect(['update', 'id' => $model->id]);
		}
		
		return $this->render('create', [
			'model' => $model,
		]);
	}
	
	/**
	 * Updates an existing User model.
	 * If update is successful, the browser will be redirected to the 'view' page.
	 *
	 * @param integer $id
	 *
	 * @return mixed
	 */
	public function actionUpdate($id)
	{
		/**
		 * @var UserForm $model
		 */
		$model = $this->findModel($id);
		
		if ($model->load(Yii::$app->request->post()) && $model->save()) {
			return $this->redirect(['update', 'id' => $model->id]);
		}
		
		return $this->render('update', [
			'model' => $model,
		]);
	}
	
	/**
	 * Deletes an existing User model.
	 * If deletion is successful, the browser will be redirected to the 'index' page.
	 *
	 * @param integer $id
	 *
	 * @return mixed
	 */
	public function actionDelete($id)
	{
		$this->findModel($id)->delete();
		
		return $this->redirect(['index']);
	}
	
}
