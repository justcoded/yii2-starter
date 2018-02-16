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
		'fieldConfig' => [
			'template'     => "{label}\n<div class=\"col-lg-3\">{input}</div>\n<div class=\"col-lg-8\">{error}</div>",
			'labelOptions' => ['class' => 'col-lg-1 control-label'],
		],
	]); ?>
	
	<?= $form->field($model, 'newPassword')->passwordInput(['autofocus' => true]) ?>
	<?= $form->field($model, 'newPasswordRepeat')->passwordInput() ?>

	<div class="form-group">
		<div class="col-lg-offset-1 col-lg-11">
			<?= Html::submitButton('Update Password', ['class' => 'btn btn-primary', 'name' => 'login-button']) ?>
		</div>
	</div>
	
	<?php ActiveForm::end(); ?>
</div>

