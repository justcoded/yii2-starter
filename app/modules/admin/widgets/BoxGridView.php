<?php

namespace app\modules\admin\widgets;

use yii\bootstrap4\LinkPager;
use yii\grid\GridView;

class BoxGridView extends GridView
{
	/**
	 * @inheritdoc
	 */
	public $options = [
		'class' => 'grid-view card',
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
	public $pager = [
		'class' => LinkPager::class,
		'listOptions' => ['class' => ['pagination pagination-sm m-0']],
	];

	/**
	 * @inheritdoc
	 */
	public $layout = '
		<div class="card-header">
			<div class="float-right">{pager}</div>
			<span class="text-muted">{summary}</span>
		</div>
		<div class="card-body p-0">{items}</div>
		<div class="card-footer"><div class="float-right">{pager}</div></div>
		';
}
