<?php

namespace justcoded\yii2\rbac\models;

use yii\data\ArrayDataProvider;
use Yii;


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
	 * @param $params
	 * @return ArrayDataProvider
	 */
	public function searchRoles($params)
	{
		$array = Yii::$app->authManager->getRoles();

		if (isset($params['role'])){
			foreach ($array as $name => $role){
				$pattern = '/'.$params['role'].'/i';
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

		if (isset($params['roles']) || isset($params['permission'])){

			if (!empty($params['roles'])){
				$array = Yii::$app->authManager->getPermissionsByRole($params['roles']);
			}else {
				$array = Yii::$app->authManager->getPermissions();
			}

			if(!empty($params['permission'])){
				foreach ($array as $name => $permission) {
					$pattern = '/' . $params['permission'] . '/i';
					if (!preg_match($pattern, $name)) {
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

	/**
	 * @return array
	 */
	public  static function getRoleByPermission()
	{
		$roles = Yii::$app->authManager->getRoles();

		$array = [];
		foreach ($roles as $role){
			$permissions = Yii::$app->authManager->getPermissionsByRole($role->name);
			foreach ($permissions as $permission) {
				if(!isset($array[$permission->name])){
					$array[$permission->name] = '';
				}
				$array[$permission->name] .= $role->name.'<br>';
			}

		}
		return $array;
	}

	/**
	 * @param $role_name
	 * @return int|null
	 */
	public static function countPermissionsByRole($role_name)
	{
		$permissions = Yii::$app->authManager->getPermissionsByRole($role_name);
		if (!is_array($permissions)) {
			return null;
		}

		return count($permissions);
	}

	/**
	 * @param $parent
	 * @return mixed|string
	 */
	public static function getInherit($parent)
	{
		if($children = Yii::$app->authManager->getChildren($parent)){
			foreach ($children as $child){
				if($child->type == static::TYPE_ROLE){
					return $child->name;
				}
			}
		}
	}
}
