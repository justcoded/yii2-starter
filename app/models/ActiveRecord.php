<?php

namespace app\models;

use Yii;
use yii\behaviors\TimestampBehavior;

/**
 * ActiveRecord model
 *
 * set timestamp behavior by default for all inherited models.
 */
class ActiveRecord extends \yii\db\ActiveRecord
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
