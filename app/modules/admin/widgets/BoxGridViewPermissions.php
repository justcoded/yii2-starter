<?php

namespace app\modules\admin\widgets;

use yii\grid\GridView;

class BoxGridViewPermissions extends GridView
{
	/**
	 * @inheritdoc
	 */
	public $options = [
		'class' => 'grid-view box',
	];

	public $filterPosition = self::FILTER_POS_HEADER;

	/**
	 * @inheritdoc
	 */
	public $tableOptions = [
		'class' => 'table table-striped',
	];

	public $button;

	/**
	 * @inheritdoc
	 */
	public $layout = '
		<div class="box-header"></div>
		<div class="box-body no-padding">{items}</div>
		<div class="box-footer"><div class="pull-right">{pager}</div></div>
		';
}
