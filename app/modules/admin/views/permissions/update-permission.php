<?php

$this->title                   = 'Update Permission';
$this->params['breadcrumbs'][] = ['label' => 'Permissions', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
$this->params['heading']       = 'Permissions';
$this->params['subheading']    = 'Update Permission';
?>

<div class="permissions-update">

	<?= $this->render('_form-permission', [
		'model' => $model,
	]) ?>

</div>
