<?php
use dmstr\widgets\Alert;
use yii\widgets\Breadcrumbs;
use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use app\modules\admin\theme\ThemeHelper;

/* @var $this \yii\web\View */

?>

<?php if (! ThemeHelper::printBlock(ThemeHelper::BLOCK_CONTENT_HEADER)) : ?>
<section class="content-header">
	<h1>
		<?= Html::encode(ArrayHelper::getValue($this->params, 'heading', $this->title)) ?>
		<?php if (!empty($this->params['subheading'])) : ?>
			<small><?= Html::encode($this->params['subheading']); ?></small>
		<?php endif; ?>

		<?php ThemeHelper::printBlock(ThemeHelper::BLOCK_HEADER_BUTTONS); ?>
	</h1>

	<?= Breadcrumbs::widget([
		'homeLink' => [
			'label' => '<i class="fa fa-dashboard"></i> Dashboard',
			'url' => ['/admin/dashboard'],
			'encode' => false,
		],
		'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
	]) ?>
</section>
<?php endif; ?>