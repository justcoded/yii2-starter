<?php
/* @var $this \yii\web\View */
/* @var $model \justcoded\yii2\rbac\forms\RoleForm */

$this->title                   = 'Add role';
$this->params['breadcrumbs'][] = ['label' => 'Permissions', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
$this->params['heading']       = 'Permissions';
$this->params['subheading']    = 'Add Role';
?>

<div class="role-create">

	<?= $this->render('_form', [
		'model' => $model,
	]) ?>

</div>

