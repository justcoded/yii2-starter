<?php

namespace app\models;

use yii\behaviors\TimestampBehavior;
use yii\helpers\ArrayHelper;
use yii\rbac\Role;
use app\models\AuthItems;


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
class AuthItemChild extends ActiveRecord
{

	public $created_at;
	public $updated_at;

	/**
	 * @inheritdoc
	 */
	public static function tableName()
	{
		return '{{%auth_item_child}}';
	}

	/**
	 * @inheritdoc
	 */
	public function rules()
	{
		return [
			[['parent', 'child'], 'required'],
			[['parent', 'child'], 'string']
		];
	}

	/**
	 * @return array
	 */
	public function behaviors()
	{
		$parent_behaviors = parent::behaviors();
		ArrayHelper::remove($parent_behaviors, TimestampBehavior::className());

		return $parent_behaviors;
	}

}
