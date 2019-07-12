<?php
//use dmstr\widgets\Alert;
//use yii\widgets\Breadcrumbs;
use yii\bootstrap4\Alert;
use yii\bootstrap4\Breadcrumbs;
use app\modules\admin\assets\AssetBundle;
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
	<link href="//fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700" rel="stylesheet">
</head>
<body class="hold-transition sidebar-mini layout-fixed">
<?php $this->beginBody() ?>

<div class="wrapper">

	<?= $this->render('../partials/header', ['adminlteAssets' => $adminlteAssets]); ?>

	<?= $this->render('../partials/nav', ['adminlteAssets' => $adminlteAssets]); ?>

	<div class="content-wrapper">
		<?= $this->render('../partials/content-header'); ?>

		<section class="content">
			<div class="container-fluid">
				<?= 0 && Alert::widget() ?>
				<?= $content ?>
			</div>
		</section>
	</div>

	<?= $this->render('../partials/footer'); ?>
</div>
<!-- ./wrapper -->

<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
