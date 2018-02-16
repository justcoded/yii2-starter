<?php

/**
 * @var \yii\web\View $this
 * @var \app\forms\RegisterForm $model
 */

use yii\bootstrap\ActiveForm;
use yii\helpers\Html;

$this->title = 'Register';
$this->params['breadcrumbs'][] = $this->title;

?>
<div class="site-register">
	<h1><?= Html::encode($this->title) ?></h1>

	<p>Please fill out the following fields to login:</p>
	
	<?php $form = ActiveForm::begin([
		'id'          => 'register-form',
		'layout'      => 'horizontal',
		'fieldConfig' => [
			'template'     => "{label}\n<div class=\"col-lg-3\">{input}</div>\n<div class=\"col-lg-8\">{error}</div>",
			'labelOptions' => ['class' => 'col-lg-1 control-label'],
		],
	]); ?>
	
	<?= $form->field($model, 'email')->textInput([
		'autofocus' => true,
		'type'      => 'email',
	]) ?>
	<?= $form->field($model, 'firstName')->textInput() ?>
	<?= $form->field($model, 'lastName')->textInput() ?>
	<?= $form->field($model, 'password')->passwordInput() ?>
	<?= $form->field($model, 'passwordRepeat')->passwordInput() ?>

	<div class="form-group">
		<div class="col-lg-offset-1 col-lg-11">
			<?= Html::submitButton('Register', ['class' => 'btn btn-primary']) ?>
		</div>
	</div>
	
	<?php ActiveForm::end(); ?>

	<div class="row">
		<div class="col-md-3 col-md-offset-1">
			<p>Already have an account? <?= Html::a('Login!', ['auth/login']) ?></p>
		</div>
	</div>
</div>