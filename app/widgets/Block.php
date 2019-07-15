<?php

namespace app\widgets;

/**
 * Block records all output between [[begin()]] and [[end()]] calls and stores it in [[\yii\base\View::$blocks]].
 * for later use.
 *
 * You can place block with `final` property to specify that block should be printed here.
 *
 * ```php
 * <?php Block::begin(['id' => 'messages']) ?>
 * Default content here.
 * <?php $this->endBlock() ?>
 * ```
 *
 * And then overriding default in sub-views:
 *
 * ```php
 * <?php $this->beginBlock('messages') ?>
 * Umm... hello?
 * <?php $this->endBlock() ?>
 * ```
 */
class Block extends \yii\widgets\Block
{
	/**
	 * @var bool whether to render the block content in place. Defaults to true,
	 * meaning the captured block content will be displayed.
	 */
	public $final = true;

	/**
	 * Ends recording a block.
	 * This method stops output buffering and saves the rendering result as a named block in the view.
	 */
	public function run()
	{
		$block = ob_get_clean();
		
		if ($this->final) {
			echo $this->view->blocks[$this->getId()] ?? $block;
		} else {
			$this->view->blocks[$this->getId()] = $block;
		}
	}
}
