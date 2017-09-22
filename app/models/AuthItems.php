<?php

namespace app\models;

use yii\rbac\Role;
use Yii;


/**
 * AuthItems model
 *
 * @property string  $name
 * @property integer $type
 * @property string  $description
 * @property string  $rule_name
 * @property string  $data
 * @property integer $created_at
 * @property integer $updated_at
 */
class AuthItems extends ActiveRecord
{
	public $permission;

	/**
	 * @inheritdoc
	 */
	public static function tableName()
	{
		return '{{%auth_item}}';
	}

	/**
	 * @inheritdoc
	 */
	public function rules()
	{
		return [
			[['type', 'name'], 'required'],
			[['name', 'description', 'rule_name', 'data'], 'string'],
			[['type', 'created_at', 'updated_at'], 'integer'],
		];
	}

	/**
	 * @return \yii\db\ActiveQuery
	 */
//	public function getChild()
//	{
//		return $this->hasMany(AuthItemChild::className(), ['parent' => 'name']);
//	}

	public function getChildItem()
	{
		return $this->hasOne(AuthItemChild::className(), ['parent' => 'name']);
	}

	/**
	 * @return $this
	 */
	public static function getRoles()
	{
		return static::find()->where(['type' => Role::TYPE_ROLE]);
	}


	/**
	 * @return array
	 */
	public function getPermissionsList()
	{
		$data = self::find()->where(['type' => Role::TYPE_PERMISSION])->asArray()->all();

		$new_data = [];
		foreach ($data as $item) {
			$new_data[$item['name']] = $item['name'];
		}
		return $new_data;
	}


	/**
	 * @return array
	 */
	public function getRolesList()
	{
		$data = self::find()->where(['type' => Role::TYPE_ROLE])->asArray()->all();

		$new_data = [];
		foreach ($data as $item) {
			$new_data[$item['name']] = $item['name'];
		}
		return $new_data;
	}


	/**
	 * @param $role_name
	 * @return \yii\rbac\Permission[]
	 */
	public function getPermissionsByRole($role_name)
	{
		return Yii::$app->authManager->getPermissionsByRole($role_name);
	}

	/**
	 * @param $role_name
	 * @return int|null
	 */
	public function countPermissionsByRole($role_name)
	{
		$permissions = $this->getPermissionsByRole($role_name);
		if (!is_array($permissions)) return null;
		return count($permissions);
	}

	/**
	 * @param $parent
	 * @return mixed|string
	 */
	public function getInherit($parent)
	{
		if($children = Yii::$app->authManager->getChildren($parent)){
			foreach ($children as $child){
				if($child->type == Role::TYPE_ROLE){
					return $child->name;
				}
			}
		}
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
}

