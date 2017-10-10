<?php
$this->title                   = 'Add Permission';
$this->params['breadcrumbs'][] = ['label' => 'Permissions', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
$this->params['heading']       = 'Permissions';
$this->params['subheading']    = 'Add Permission';
?>

<div class="role-create">

	<?= $this->render('_form', [
		'model' => $model,
	]) ?>

</div>

