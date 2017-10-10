<?php
/* @var $this \yii\web\View */
/* @var $model \justcoded\yii2\rbac\forms\ScanForm */

use yii\bootstrap\ActiveForm;
use yii\helpers\Html;

$this->title = 'Scan Routes Permissions';
$this->params['breadcrumbs'][] = ['label' => 'Permissions', 'url' => ['permissions/']];
$this->params['breadcrumbs'][] = $this->title;
$this->params['heading'] = 'Permissions';
$this->params['subheading'] = 'Scan routes';
?>

<div class="row">
	<div class="col-md-8 col-sm-12">
		<?php $form = ActiveForm::begin(); ?>
		<div class="panel box scan-routes">
			<div class="panel-body box-body">
				<p>You can scan your controllers to find routes and create permissions for them automatically.</p>
				<p>Please note, that for permission names we use a path of Controller Unique ID and Action ID,
					not friendly URLs (specified inside UrlManager configuration).</p>

				<?= $form->field($model, 'path')->textInput(['maxlength' => true, 'placeholder' => 'Ex.: @app']) ?>

				<?= $form->field($model, 'ignorePath')->textInput([
					'value' => is_array($model->ignorePath) ? implode(',', $model->ignorePath) : '',
					'placeholder' => 'Ex.: /commands,/modules/api',
				]) ?>

				<?= $form->field($model, 'routesBase')->textInput(['maxlength' => true]) ?>

			</div>
			<div class="box-footer text-right">
				<?= Html::submitButton('Scan', ['class' => 'btn btn-success']) ?>
			</div>
		</div>
		<?php ActiveForm::end(); ?>
	</div>
</div>
