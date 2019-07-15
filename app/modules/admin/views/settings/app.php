<?php

use yii\helpers\Html;
use yii\bootstrap4\ActiveForm;

/* @var $this yii\web\View */
/* @var $model justcoded\yii2\settings\forms\AppSettingsForm */

$this->title                   = 'App settings';
$this->params['breadcrumbs'][] = $this->title;
$this->params['heading']       = 'Settings';
$this->params['subheading']    = 'App';

?>
	
<div class="user-form card">

	<?php $form = ActiveForm::begin([
		'layout' => 'horizontal',
	]); ?>

	<?= $form->errorSummary($model); ?>
	
	<div class="card-body">
		<h4>Admin Email</h4>
		<?= $form->field($model, 'adminName')->textInput()->label('Name') ?>
		<?= $form->field($model, 'adminEmail')->input('email')->label('Email') ?>

		<h4>System Email</h4>
		<?= $form->field($model, 'systemName')->textInput()->label('Name') ?>
		<?= $form->field($model, 'systemEmail')->input('email')->label('Email') ?>

		<h4>Authentication</h4>
		<?= $form->field($model, 'passwordResetToken')->input('number') ?>
		<?= $form->field($model, 'rememberMeExpiration')->input('number') ?>
	</div>
	<div class="card-footer text-right">
		<?= Html::submitButton('Save', ['class' => 'btn btn-primary']) ?>
	</div>

	<?php ActiveForm::end(); ?>
</div>