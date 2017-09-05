<?php
use dmstr\widgets\Alert;
use yii\widgets\Breadcrumbs;
use app\modules\admin\theme\AssetBundle;
use yii\helpers\Html;
use yii\helpers\ArrayHelper;

/* @var $this \yii\web\View */
/* @var $content string */

AssetBundle::register($this);

$adminlteAssets = Yii::$app->assetManager->getPublishedUrl('@vendor/almasaeed2010/adminlte/dist');

$this->beginPage() ?>
<!DOCTYPE html>
<html>
<head>
	<meta charset="<?= Yii::$app->charset ?>"/>
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<?= Html::csrfMetaTags() ?>
	<title><?= Html::encode($this->title) ?></title>
	<?php $this->head() ?>
</head>
<body class="hold-transition skin-blue sidebar-mini">
<?php $this->beginBody() ?>

<div class="wrapper">

	<?= $this->render('/partials/header', ['adminlteAssets' => $adminlteAssets]); ?>

	<?= $this->render('/partials/nav', ['adminlteAssets' => $adminlteAssets]); ?>

	<div class="content-wrapper">
		<section class="content-header">
			<h1>
				<?= Html::encode(ArrayHelper::getValue($this->params, 'heading', $this->title)) ?>
				<?php if (!empty($this->params['subheading'])) : ?>
					<small><?= Html::encode($this->params['subheading']); ?></small>
				<?php endif; ?>
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

		<section class="content">
			<?= Alert::widget() ?>
			<?= $content ?>
		</section>
	</div>

	<?= $this->render('/partials/footer'); ?>
</div>
<!-- ./wrapper -->

<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
