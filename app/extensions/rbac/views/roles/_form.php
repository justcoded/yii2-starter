<?php

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use justcoded\yii2\rbac\forms\ItemForm;
?>

<div class="role-form box">

	<?php $form = ActiveForm::begin([
			'layout' => 'horizontal',
	        'enableAjaxValidation' => true,
	]); ?>

	<div class="box-body">
		<?= $form->field($model, 'name')->textInput([
		        'maxlength' => true,
		        'readonly' => $model->scenario == ItemForm::SCENARIO_CREATE ? false : true
            ]) ?>

		<?= $form->field($model, 'description')->textInput(['maxlength' => true]) ?>

		<?= $form->field($model, 'inherit_permissions')->inline()
            ->checkboxList($model->listInheritPermissions, [
                    'value' => $model->inheritPermissions,
                ]) ?>
        <p class="text-center">* Permissions will be updated only after Save</p>

        <?= $form->field($model, 'allow_permissions')
            ->hiddenInput(['value' => $model->allowPermissions])
            ->label(false); ?>

		<?= $form->field($model, 'deny_permissions')
            ->hiddenInput()
            ->label(false); ?>

        <div class="row">
            <div class="col-md-offset-2 col-md-4">
	            <?= Html::label($model->getAttributeLabel('allow_permissions')) ?>
                <input type="text" id="allowSearch" class="form-control" placeholder="Search...">
                <div id="allow-permissions">
                    <ul id="allowUL">
	                <?php if ($model->treeAllowPermissions()): ?>
                        <?= $model->treeAllowPermissions() ?>
	                <?php endif; ?>
                    </ul>
                </div>
            </div>

            <div class="col-md-1 icon-block no-padding">
                <i class="fa fa-angle-double-right fa-4x"></i><br>
                <i class="fa fa-angle-double-left fa-4x"></i>
            </div>

            <div class="col-md-4">
	            <?= Html::label($model->getAttributeLabel('deny_permissions')) ?>
                <input type="text" id="denySearch" class="form-control" placeholder="Search...">
                <div id="deny-permissions">
                    <ul id="dennyUL">
	                    <?php if ($model->treeDennyPermissions()): ?>
		                    <?= $model->treeDennyPermissions() ?>
	                    <?php endif; ?>
                    </ul>
                </div>
            </div>

        </div>

	</div>
	<div class="box-footer text-right">
		<?= Html::submitButton('Save' , ['class' => 'btn btn-success']) ?>
        <?= Html::a('delete', ['delete', 'name' => $model->name], ['class' => 'delete', 'data-method' => 'post']) ?>
	</div>

	<?php ActiveForm::end(); ?>
</div>

