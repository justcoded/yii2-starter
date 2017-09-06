<?php

namespace app\models;

use Yii;
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
			TimestampBehavior::className(),
		];
	}

	/**
	 * @inheritdoc
	 * @return static ActiveRecord instance matching the condition, or throw exception.
	 * @throws NotFoundHttpException
	 */
	public static function findOneOrFail($id)
	{
		if (($model = static::findOne($id)) !== null) {
			return $model;
		}

		throw new NotFoundHttpException('The requested page does not exist.');
	}

}
