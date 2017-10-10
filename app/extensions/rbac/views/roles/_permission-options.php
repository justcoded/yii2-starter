<?php

/* @var $treeItems array */

use yii\helpers\Html;

?>
<?php foreach ($treeItems as $item) : ?>
	<option value="<?= Html::encode($item['name']) ?>" data-weight="<?= $item['order']; ?>"
	        style="padding-left: <?= $item['depth'] * 20; ?>px;" selected="selected">
		<?= Html::encode($item['name']) ?>
	</option>
<?php endforeach; ?>
