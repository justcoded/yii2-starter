<?php

namespace justcoded\yii2\rbac\models;

/**
 * AuthRule model
 *
 * @property string  $name
 * @property string  $data
 * @property integer $created_at
 * @property integer $updated_at
 */
class AuthRule extends ActiveRecord
{

	/**
	 * @inheritdoc
	 */
	public static function tableName()
	{
		return '{{%auth_rule}}';
	}

	/**
	 * @inheritdoc
	 */
	public function rules()
	{
		return array_merge(parent::rules(), [
			[['name'], 'required'],
			[['name', 'data'], 'string'],
			[['created_at', 'updated_at'], 'integer'],
		]);
	}

}
