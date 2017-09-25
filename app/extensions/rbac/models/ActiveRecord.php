<?php

namespace justcoded\yii2\rbac\models;

use yii\behaviors\TimestampBehavior;

/**
 * ActiveRecord model
 *
 * set timestamp behavior by default for all inherited models.
 */
abstract class ActiveRecord extends \yii\db\ActiveRecord
{
	/**
	 * @inheritdoc
	 */
	public function behaviors()
	{
		return [
			TimestampBehavior::className(),
		];
	}
}
