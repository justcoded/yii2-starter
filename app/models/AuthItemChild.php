<?php

namespace app\models;

use yii\behaviors\TimestampBehavior;
use yii\helpers\ArrayHelper;

/**
 * AuthItemChild model
 *
 * @property string  $parent
 * @property string  $child
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


	/**
	 * @return \yii\db\ActiveQuery
	 */
	public function getParentItem()
	{
		return $this->hasOne(AuthItems::className(), ['name' => 'parent']);
	}

	/**
	 * @return \yii\db\ActiveQuery
	 */
	public function getChildItem()
	{
		return $this->hasOne(AuthItems::className(), ['name' => 'child']);
	}
}

