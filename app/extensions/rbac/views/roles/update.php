<?php
use justcoded\yii2\rbac\assets\RbacAssetBundle;

RbacAssetBundle::register($this);

$this->title                   = 'Update role';
$this->params['breadcrumbs'][] = ['label' => 'Permissions', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
$this->params['heading']       = 'Permissions';
$this->params['subheading']    = 'Update Role';
?>

<div class="update">

	<?= $this->render('_form', [
		'model' => $model,
	]) ?>

</div>

