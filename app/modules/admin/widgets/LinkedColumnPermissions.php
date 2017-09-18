<?php

namespace app\modules\admin\widgets;

use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\grid\DataColumn;

class LinkedColumnPermissions extends DataColumn
{
	public $url = [ 'update' ];
	//public $primaryKey = 'name';
	//public $queryVar = 'name';
	
	/**
	 * @inheritdoc
	 */
	protected function renderDataCellContent($model, $key, $index)
	{
		$text = parent::renderDataCellContent($model, $key, $index);
		//return Html::a($text, ArrayHelper::merge($this->url, [$this->queryVar => $model->{$this->primaryKey}]));
		return Html::a($text, '');
	}
}

