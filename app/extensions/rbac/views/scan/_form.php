<?php

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use justcoded\yii2\rbac\forms\ItemForm;
?>

<div class="scan-form box">

	<?php $form = ActiveForm::begin([
			'layout' => 'horizontal',
	]); ?>

	<div class="box-body">
		<?= $form->field($model, 'path')->textInput() ?>

		<?= $form->field($model, 'ignorePath')->textInput(['maxlength' => true]) ?>

		<?= $form->field($model, 'routesBase')->textInput(['maxlength' => true]) ?>

	</div>
	<div class="box-footer text-right">
		<?= Html::submitButton('Save' , ['class' => 'btn btn-success']) ?>
	</div>

	<?php ActiveForm::end(); ?>
</div>

