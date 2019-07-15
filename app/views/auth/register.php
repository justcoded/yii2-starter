<?php

/**
 * @var \yii\web\View $this
 * @var \app\forms\RegisterForm $model
 */

use yii\bootstrap4\ActiveForm;
use yii\helpers\Html;

$this->title = 'Register';
$this->params['breadcrumbs'][] = $this->title;

?>
<div class="site-register col-md-6 mx-auto">
	<h1><?= Html::encode($this->title) ?></h1>

	<p>Please fill out the following fields to login:</p>
	
	<?php $form = ActiveForm::begin([
		'id'          => 'register-form',
	]); ?>
	
	<?= $form->field($model, 'email')->textInput([
		'type'      => 'email',
	]) ?>
	<?= $form->field($model, 'firstName')->textInput() ?>
	<?= $form->field($model, 'lastName')->textInput() ?>
	<?= $form->field($model, 'password')->passwordInput() ?>
	<?= $form->field($model, 'passwordRepeat')->passwordInput() ?>

	<div class="form-group">
		<?= Html::submitButton('Register', ['class' => 'btn btn-primary']) ?>
	</div>
	
	<?php ActiveForm::end(); ?>

	<div class="pt-3">
		<p>Already have an account? <?= Html::a('Login!', ['auth/login']) ?></p>
	</div>
</div>