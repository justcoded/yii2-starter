<?php

namespace app\modules\admin\assets;

class ThemeHelper
{
	const BLOCK_CONTENT_HEADER = 'content-header';
	const BLOCK_HEADER_BUTTONS = 'header-buttons';

	/**
	 * Print blocks content if exists
	 *
	 * @param string $block_id
	 *
	 * @return bool
	 */
	public static function printBlock($block_id)
	{
		$view = \Yii::$app->getView();

		if (isset($view->blocks[$block_id])) {
			echo $view->blocks[$block_id];
			return true;
		}

		return false;
	}
}
