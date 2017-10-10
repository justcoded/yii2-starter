<?php

use app\models\User;
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

/* @var $this yii\web\View */
/* @var $model \app\modules\admin\forms\UserForm */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="user-form box">

	<?php $form = ActiveForm::begin([
			'layout' => 'horizontal',
	]); ?>

	<div class="box-body">
		<?= $form->field($model, 'first_name')->textInput(['maxlength' => true]) ?>

		<?= $form->field($model, 'last_name')->textInput(['maxlength' => true]) ?>

		<?= $form->field($model, 'username')->textInput(['maxlength' => true]) ?>

		<?= $form->field($model, 'email')->textInput(['maxlength' => true]) ?>

		<?= $form->field($model, 'password_repeat')
			->passwordInput()->label('Password') ?>

		<?= $form->field($model, 'password')
			->passwordInput()->label('Password Repeat') ?>

		<?= $form->field($model, 'status')->dropDownList(User::getStatusesList()) ?>
	</div>
	<div class="box-footer text-right">
		<?= Html::submitButton($model->isNewRecord? 'Save' : 'Update', ['class' => 'btn btn-success']) ?>
	</div>

	<?php ActiveForm::end(); ?>
</div>
