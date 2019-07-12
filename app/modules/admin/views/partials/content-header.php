<?php
use dmstr\widgets\Alert;
use yii\widgets\Breadcrumbs;
use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use app\modules\admin\assets\ThemeHelper;

/* @var $this \yii\web\View */

?>

<?php if (! ThemeHelper::printBlock(ThemeHelper::BLOCK_CONTENT_HEADER)) : ?>
	<!-- Content Header (Page header) -->
	<div class="content-header">
		<div class="container-fluid">
			<div class="row mb-2">
				<div class="col-sm-6">
					<h1 class="m-0 text-dark">
						<?= Html::encode(ArrayHelper::getValue($this->params, 'heading', $this->title)) ?>
						<?php if (!empty($this->params['subheading'])) : ?>
							<small><?= Html::encode($this->params['subheading']); ?></small>
						<?php endif; ?>

						<?php ThemeHelper::printBlock(ThemeHelper::BLOCK_HEADER_BUTTONS); ?>
					</h1>
				</div><!-- /.col -->
				<div class="col-sm-6">
					<?= Breadcrumbs::widget([
						'homeLink' => [
							'label' => 'Dashboard',
							'url' => ['/admin/dashboard'],
							'encode' => false,
						],
						'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
						'tag' => 'ol',
						'itemTemplate' => "<li class=\"breadcrumb-item\">{link}</li>\n",
						'activeItemTemplate' => "<li class=\"breadcrumb-item active\">{link}</li>\n",
						'options' => [
							'class' => 'breadcrumb float-sm-right',
						],
					]) ?>
				</div><!-- /.col -->
			</div><!-- /.row -->
		</div><!-- /.container-fluid -->
	</div>
<?php endif; ?>