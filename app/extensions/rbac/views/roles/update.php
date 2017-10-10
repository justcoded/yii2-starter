<?php
/* @var $this \yii\web\View */
/* @var $model \justcoded\yii2\rbac\forms\RoleForm */
/* @var $role \justcoded\yii2\rbac\models\Role */

$this->title                   = 'Update role';
$this->params['breadcrumbs'][] = ['label' => 'Permissions', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
$this->params['heading']       = 'Permissions';
$this->params['subheading']    = 'Update Role';
?>

<div class="update">

	<?= $this->render('_form', [
		'model' => $model,
		'role'  => $role,
	]) ?>

</div>

