<?php
/**
 * @var \yii\web\View $this
 * @var \app\forms\PasswordUpdateForm $model
 */

use yii\bootstrap\ActiveForm;
use yii\helpers\Html;

$this->title = 'Update Password';
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="site-forgot-password">
	<h1><?= Html::encode($this->title) ?></h1>
	
	<?php $form = ActiveForm::begin([
		'id'          => 'update-password-form',
		'layout'      => 'horizontal',
	]); ?>
	
	<?= $form->field($model, 'newPassword')->passwordInput(['autofocus' => true]) ?>
	<?= $form->field($model, 'newPasswordRepeat')->passwordInput() ?>

	<div class="form-group">
		<div class="col-md-offset-3 col-md-6">
			<?= Html::submitButton('Update Password', ['class' => 'btn btn-primary', 'name' => 'login-button']) ?>
		</div>
	</div>
	
	<?php ActiveForm::end(); ?>
</div>

