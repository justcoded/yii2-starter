<?php

namespace app\modules\admin\widgets;

use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\grid\DataColumn;

class LinkedColumn extends DataColumn
{
	public $url = [ 'update' ];
	public $primaryKey = 'id';
	public $queryVar = 'id';
	
	/**
	 * @inheritdoc
	 */
	protected function renderDataCellContent($model, $key, $index)
	{
		$text = parent::renderDataCellContent($model, $key, $index);
		return Html::a($text, ArrayHelper::merge($this->url, [$this->queryVar => $model->{$this->primaryKey}]));
	}
}

