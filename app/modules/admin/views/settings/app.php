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
			<div class="row">
				<div class="col-md-6">
					<?= $form->field($model, 'adminName')->textInput() ?>
				</div>
				<div class="col-md-6">
					<?= $form->field($model, 'adminEmail')->textInput() ?>
				</div>
			</div>
			
			<div class="row">
				<div class="col-md-6">
					<?= $form->field($model, 'senderName')->textInput() ?>
				</div>
				<div class="col-md-6">
					<?= $form->field($model, 'senderEmail')->textInput() ?>
				</div>
			</div>
			
			<?= $form->field($model, 'passwordResetToken')->input('number') ?>
			
			<?= $form->field($model, 'rememberMeExpiration')->input('number') ?>
		
		</div>
		<div class="box-footer text-right">
			<?= Html::submitButton('Save', ['class' => 'btn btn-primary']) ?>
		</div>
	
	<?php ActiveForm::end(); ?>
</div>