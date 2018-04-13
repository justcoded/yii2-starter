<?php

namespace app\models;

use yii\behaviors\TimestampBehavior;
use yii\web\NotFoundHttpException;

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
			TimestampBehavior::class,
		];
	}
}
