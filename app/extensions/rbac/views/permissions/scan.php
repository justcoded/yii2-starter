<?php
use justcoded\yii2\rbac\assets\RbacAssetBundle;
use yii\bootstrap\ActiveForm;
use yii\helpers\Html;

RbacAssetBundle::register($this);

$this->title                   = 'Scan Routes';
$this->params['breadcrumbs'][] = ['label' => 'Permissions', 'url' => ['permissions/']];
$this->params['breadcrumbs'][] = $this->title;
$this->params['heading']       = 'Scan';
$this->params['subheading']    = 'scan routes';
?>

<div class="scan box">

		<?php $form = ActiveForm::begin([
			'layout' => 'horizontal',
		]); ?>

        <div class="box-body">
			<?= $form->field($model, 'path')->textInput(['maxlength' => true]) ?>

			<?= $form->field($model, 'ignorePath')->textInput(['value' => is_array($model->ignorePath) ? implode(',', $model->ignorePath) : '']) ?>

			<?= $form->field($model, 'routesBase')->textInput(['maxlength' => true]) ?>

        </div>
        <div class="box-footer text-right">
			<?= Html::submitButton('Scan' , ['class' => 'btn btn-success']) ?>
        </div>

		<?php ActiveForm::end(); ?>

</div>
