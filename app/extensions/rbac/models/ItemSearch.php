<?php

namespace justcoded\yii2\rbac\models;

use yii\data\ArrayDataProvider;
use Yii;
use ReflectionClass;


class ItemSearch extends Item
{

	public $name;
	public $role;
	public $roles;

	/**
	 * @inheritdoc
	 */
	public function rules()
	{
		return [
			[['name', 'role', 'roles'], 'safe'],
		];
	}

	/**
	 * @return string
	 */
	public function formName()
	{
		$reflector = new ReflectionClass($this);
		return $reflector->getShortName();
	}


	/**
	 * @param $params
	 * @return ArrayDataProvider
	 */
	public function searchRoles($params)
	{
		$array = Yii::$app->authManager->getRoles();

		if (isset($params['role'])){
			foreach ($array as $name => $role){
				$pattern = '/^'.$params['role'].'/i';
				if (!preg_match( $pattern, $name)){
					unset($array[$name]);
				}
			}
		}

		$dataProvider = new ArrayDataProvider([
			'allModels' => $array,
			'pagination' => [
				'pageSize' => 10,
			],
			'sort' => [
				'attributes' => ['name'],
			],
		]);

		return $dataProvider;
	}

	/**
	 * @param $params
	 * @return ArrayDataProvider
	 */
	public function searchPermissions($params)
	{
		$array = Yii::$app->authManager->getPermissions();

		if (!empty($params['ItemSearch']) || !empty($params['permission'])){
			if (isset($params['ItemSearch'])) {
				if ($params['ItemSearch']['roles'] == 'All'){
					$array = Yii::$app->authManager->getPermissions();
				}else {
					$array = Yii::$app->authManager->getPermissionsByRole($params['ItemSearch']['roles']);
				}
			}elseif(isset($params['permission'])){
				$array = Yii::$app->authManager->getPermissions();
				foreach ($array as $name => $permission){
					$pattern = '/^'.$params['permission'].'/i';
					if (!preg_match( $pattern, $name)){
						unset($array[$name]);
					}
				}
			}
		}

		$dataProvider = new ArrayDataProvider([
			'allModels' => $array,
			'pagination' => [
				'pageSize' => 10,
			],
			'sort' => [
				'attributes' => ['name'],
			],
		]);
		return $dataProvider;
	}

}
