<?php
/**
 * @var \yii\web\View $this
 * @var \app\forms\PasswordUpdateForm $model
 */

use yii\bootstrap4\ActiveForm;
use yii\helpers\Html;

$this->title = 'Update Password';
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="site-forgot-password col-md-6 mx-auto">
	<h1><?= Html::encode($this->title) ?></h1>
	
	<?php $form = ActiveForm::begin([
		'id'          => 'update-password-form',
	]); ?>
	
	<?= $form->field($model, 'newPassword')->passwordInput(['autofocus' => true]) ?>
	<?= $form->field($model, 'newPasswordRepeat')->passwordInput() ?>

	<div class="form-group">
		<?= Html::submitButton('Update Password', ['class' => 'btn btn-primary', 'name' => 'login-button']) ?>
	</div>
	
	<?php ActiveForm::end(); ?>
</div>
