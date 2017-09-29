<?php

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

?>

<div class="role-form box">

	<?php $form = ActiveForm::begin([
			'layout' => 'horizontal',
	        'enableAjaxValidation' => true,
	]); ?>

	<div class="box-body">
		<?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>

		<?= $form->field($model, 'description')->textInput(['maxlength' => true]) ?>

		<?= $form->field($model, 'inherit_permissions')->inline()
            ->checkboxList($model->listInheritPermissions, [
                    'value' => $model->inheritPermissions,
                ]) ?>
        <p class="text-center">* Permissions will be updated only after Save</p>

        <?= $form->field($model, 'allow_permissions')
            ->textInput(['value' => $model->allowPermissions])
            ->label(false); ?>

		<?= $form->field($model, 'deny_permissions')
            ->hiddenInput()
            ->label(false); ?>

        <div class="row">
            <div class="col-md-offset-2 col-md-4">
	            <?= Html::label($model->getAttributeLabel('allow_permissions')) ?>
                <div id="allow-permissions">

<!--	                --><?php //if ($model->arrayAllowPermissions()): ?>
<!--                        --><?//= $model->arrayAllowPermissions() ?>
<!--	                --><?php //endif; ?>
                </div>
            </div>
            <div class="col-md-1 icon-block no-padding">
                <i class="fa fa-angle-double-right fa-4x"></i><br>
                <i class="fa fa-angle-double-left fa-4x"></i>
            </div>
            <div class="col-md-4">
	            <?= Html::label($model->getAttributeLabel('deny_permissions')) ?>
                <div id="deny-permissions"></div>
            </div>
        </div>

	</div>
	<div class="box-footer text-right">
		<?= Html::submitButton('Save' , ['class' => 'btn btn-success']) ?>
        <?= Html::a('delete', ['delete', 'name' => $model->name], ['class' => 'delete', 'data-method' => 'post']) ?>
	</div>

	<?php ActiveForm::end(); ?>
</div>
