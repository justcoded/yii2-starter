<?php

use yii\bootstrap\ActiveForm;
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\extensions\settings\forms\SettingsForm */
?>

<?php $form = ActiveForm::begin(); ?>
	
	<section class="content">
		<?= Html::errorSummary($model); ?>
		
		<div class="row">
			<div class="col-lg-9">
				<div class="box">
					<div class="box-header with-border">
						<h3 class="box-title"><?= $this->title ?></h3>
					</div>
					<div class="box-body">
						<?= $form->field($model, 'adminName')->textInput() ?>
						
						<?= $form->field($model, 'adminEmail')->textInput() ?>
						
						<?= $form->field($model, 'systemName')->textInput() ?>
						
						<?= $form->field($model, 'systemEmail')->textInput() ?>
						
						<?= $form->field($model, 'passwordResetToken')->input('number') ?>
						
						<?= $form->field($model, 'rememberMeExpiration')->input('number') ?>
					
					</div>
					<div class="box-footer">
						<div class="pull-right">
							<div class="form-group">
								<?= Html::submitButton('Save', ['class' => 'btn btn-primary']) ?>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</section>

<?php ActiveForm::end(); ?>