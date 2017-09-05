<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model \app\modules\admin\forms\UserForm */

$this->title                   = 'Create User';
$this->params['breadcrumbs'][] = ['label' => 'Users', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
$this->params['heading']       = 'Users';
$this->params['subheading']    = 'Add New';
?>
<div class="user-create">

	<?= $this->render('_form', [
		'model' => $model,
	]) ?>

</div>
