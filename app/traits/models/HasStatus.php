<?php

namespace app\traits\models;

use app\models\ActiveRecord;
use yii\helpers\ArrayHelper;

/**
 * Trait HasStatus
 *
 * @property int $status
 *
 * @package app\traits\models
 */
trait HasStatus
{
	public static function getStatusesList()
	{
		return [
			ActiveRecord::STATUS_ACTIVE => 'Active',
		];
	}

	public function getStatusAlias()
	{
		return ArrayHelper::getValue(static::getStatusesList(), $this->status);
	}
}