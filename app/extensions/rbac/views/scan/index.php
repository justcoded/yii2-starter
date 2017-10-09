<?php
use justcoded\yii2\rbac\assets\RbacAssetBundle;

RbacAssetBundle::register($this);

$this->title                   = 'Scan Routes';
$this->params['breadcrumbs'][] = ['label' => 'Permissions', 'url' => ['permissions/']];
$this->params['breadcrumbs'][] = $this->title;
$this->params['heading']       = 'Scan';
$this->params['subheading']    = 'scan routes';
?>

<div class="scan">

	<?= $this->render('_form', [
		'model' => $model,
	]) ?>

</div>
