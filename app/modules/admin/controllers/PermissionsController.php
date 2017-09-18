<?php

namespace app\modules\admin\controllers;

use app\console\Application;
use app\models\AuthItemChild;
use app\models\AuthItems;
use app\modules\admin\forms\PermissionForm;
use app\modules\admin\forms\RoleForm;
use app\modules\admin\models\AuthItemSearch;
use Yii;
use app\modules\admin\forms\UserForm;
use app\traits\controllers\FindModelOrFail;
use app\models\User;
use app\modules\admin\models\UserSearch;
use yii\filters\VerbFilter;
use vova07\console\ConsoleRunner;
use yii\web\Response;
use yii\rbac\Role;

/**
 * UsersController implements the CRUD actions for User model.
 */
class PermissionsController extends Controller
{
	use FindModelOrFail;


	public $enableCsrfValidation = false;
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
	 * Lists all User models.
	 *
	 * @return mixed
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
		$model->scenario = 'add-role';

		if ($model->load(Yii::$app->request->post()) && $model->save()) {

			foreach ($model->permissions as $permission){
				$new_permission = new AuthItemChild([
					'parent' => $model->name,
					'child' => $permission
				]);
				$new_permission->save();
			}

			return $this->redirect(['index']);
		}

		return $this->render('create-role', [
			'model' => $model,
		]);

	}

	public function actionAddPermission()
	{
		$model = new PermissionForm();

		if ($model->load(Yii::$app->request->post())){
			if (!$model->save()){
				return pa($model->errors);
			}

			$name = $model->name;

			if (!empty($model->parent_roles)) {
				$array_parent_roles = explode(',', $model->parent_roles);
				foreach ($array_parent_roles as $role) {
					$new_child = new AuthItemChild([
						'parent' => $role,
						'child'  => $name
					]);
					$new_child->save();
				}
			}
			if (!empty($model->parent_permissions)) {
				$array_parent_permissions = explode(',', $model->parent_permissions);
				foreach ($array_parent_permissions as $permission) {
					$new_child = new AuthItemChild([
						'parent' => $permission,
						'child'  => $name
					]);
					$new_child->save();
				}
			}
			if (!empty($model->children_permissions)) {
				$array_children_permissions = explode(',', $model->children_permissions);
				foreach ($array_children_permissions as $permission) {
					$new_child = new AuthItemChild([
						'parent' => $name,
						'child'  => $permission,
					]);
					$new_child->save();
				}
			}

			return $this->redirect(['index']);
		}

		return $this->render('create-permission', [
			'model' => $model
		]);
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

	/**
	 * @return bool|string
	 */
	public function actionAjaxPermissions()
	{
		if (!Yii::$app->request->isAjax){
			return false;
		}
		Yii::$app->response->format = Response::FORMAT_HTML;

		if($request = Yii::$app->request->post('role')){
			$permissions = Yii::$app->authManager->getPermissionsByRole($request);

		}else{
			$permissions =  Yii::$app->authManager->getPermissions();
		}

		$data = '';
		foreach ($permissions as $permission){
			$data .= '<div class="permissions" data-name="' . $permission->name . '">'.$permission->name . '</div>';
		}
		return $data;
	}

}
