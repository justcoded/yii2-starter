<?php

/* @var $this yii\web\View */
/* @var $model \app\modules\admin\forms\UserForm */
/* @var $roles array */

$this->title = "Update {$model->fullName}";
$this->params['breadcrumbs'][] = ['label' => 'Users', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->fullName, 'url' => ['update', 'id' => $model->id]];
$this->params['heading'] = 'Users';
$this->params['subheading'] = $model->fullName;
?>
<div class="user-update">
	
	<?= $this->render('_form', [
		'model' => $model,
	]) ?>

</div>
