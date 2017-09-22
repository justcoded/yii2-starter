<?php

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

/* @var $this yii\web\View */
/* @var $model justcoded\yii2\settings\forms\SettingsForm */

$this->title                   = 'App settings';
$this->params['breadcrumbs'][] = $this->title;
$this->params['heading']       = 'Settings';
$this->params['subheading']    = 'App';

?>
	
<div class="user-form box">

	<?php $form = ActiveForm::begin([
		'layout' => 'horizontal',
	]); ?>
	
	<?= Html::errorSummary($model); ?>
		
		<div class="box-body">
			<h4 class="col-md-offset-1">Admin Email</h4>
			<?= $form->field($model, 'adminName')->textInput()->label('Name') ?>
			<?= $form->field($model, 'adminEmail')->textInput()->label('Email') ?>

			<h4 class="col-md-offset-1">System Email</h4>
			<?= $form->field($model, 'systemName')->textInput()->label('Name') ?>
			<?= $form->field($model, 'systemEmail')->textInput()->label('Email') ?>

			<h4 class="col-md-offset-1">Authentication</h4>
			<?= $form->field($model, 'passwordResetToken')->input('number') ?>
			<?= $form->field($model, 'rememberMeExpiration')->input('number') ?>
		</div>
		<div class="box-footer text-right">
			<?= Html::submitButton('Save', ['class' => 'btn btn-primary']) ?>
		</div>
	
	<?php ActiveForm::end(); ?>
</div>