<?php

namespace app\traits\controllers;

use app\models\ActiveRecord;
use yii\base\InvalidCallException;
use yii\web\NotFoundHttpException;

trait FindModelOrFail
{
	/**
	 * @var string
	 */
	protected $modelClass;

	/**
	 * Finds model of $modelClass and throw 404 exception if failed.
	 *
	 * @param integer $id ActiveRecord model ID
	 *
	 * @return ActiveRecord instance matching the condition, or throw exception.
	 * @throws NotFoundHttpException
	 */
	protected function findModel($id)
	{
		if (empty($this->modelClass)) {
			throw new InvalidCallException(__CLASS__ . '::findModel() method requires $this->modelClass property to be set.');
		}
		if (($model = call_user_func_array([$this->modelClass, 'findOne'], [$id])) !== null) {
			return $model;
		}
		throw new NotFoundHttpException('The requested page does not exist.');
	}
}