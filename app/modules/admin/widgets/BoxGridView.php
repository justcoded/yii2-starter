<?php

namespace app\modules\admin\widgets;

use yii\grid\GridView;

class BoxGridView extends GridView
{
	/**
	 * @inheritdoc
	 */
	public $options = [
		'class' => 'grid-view box',
	];

	/**
	 * @inheritdoc
	 */
	public $tableOptions = [
		'class' => 'table table-striped',
	];

	/**
	 * @inheritdoc
	 */
	public $layout = '
		<div class="box-header">
			<div class="pull-right">{pager}</div>
			{summary}
		</div>
		<div class="box-body no-padding">{items}</div>
		<div class="box-footer"><div class="pull-right">{pager}</div></div>
		';
}
