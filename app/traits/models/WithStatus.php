<?php

namespace app\traits\models;

use app\models\ActiveRecord;
use app\models\User;
use yii\helpers\ArrayHelper;

/**
 * Trait HasStatus
 *
 * @property int $status
 *
 * @package app\traits\models
 */
trait WithStatus
{
	public static function getStatusesList()
	{
		return [
		];
	}

	public function getStatusAlias()
	{
		return ArrayHelper::getValue(static::getStatusesList(), $this->status);
	}
}
