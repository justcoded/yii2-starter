<?php

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use kartik\select2\Select2;
use yii\helpers\Url;
?>

<div class="role-form box">

	<?php $form = ActiveForm::begin([
	        'action' => ['store-roles'],
			'layout' => 'horizontal',
	        'enableAjaxValidation' => true,
	        'validationUrl' => Url::toRoute('permissions/validate-role')
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
            ->hiddenInput(['value' => $model->allowPermissions])
            ->label(false); ?>

        <div class="row">
            <div class="col-md-offset-2 col-md-4">
                <?= Html::label($model->getAttributeLabel('allow_permissions')) ?>
            </div>
            <div class="col-md-offset-1 col-md-4">
	            <?= Html::label($model->getAttributeLabel('deny_permissions')) ?>
	            <?= $form->field($model, 'deny_permissions')->hiddenInput()->label(false); ?>
            </div>
        </div>

        <div class="row">
            <div class="col-md-offset-2 col-md-4">
                <div class="col-sm-9 no-padding">
                    <?= Select2::widget([
                        'model' => $model,
                        'attribute' => 'permissions_search',
                        'data' => $model->permissionsList,
                        'options' => ['placeholder' => 'Search permissions ...'],
                        'pluginOptions' => [
                            'allowClear' => true
                        ],
                    ]);?>
                </div>
                <div class="col-sm-3 no-padding">
                    <?= Html::button('Add', [
                        'class' => 'btn btn-md btn-default no-border-radius',
                        'style' => 'width:100%',
                        'id' => 'permissions_search'
                    ]) ?>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-offset-2 col-md-4">
                <div id="allow-permissions">
	                <?php if ($model->allowPermissions): ?>
		                <?php foreach (explode(',', $model->allowPermissions) as $permission): ?>
                            <div class="permissions" data-name="<?= $permission ?>"><?= $permission ?></div>
		                <?php endforeach; ?>
	                <?php endif; ?>
                </div>
            </div>
            <div class="col-md-1 icon-block no-padding">
                <i class="fa fa-angle-double-right fa-4x"></i><br>
                <i class="fa fa-angle-double-left fa-4x"></i>
            </div>
            <div class="col-md-4">
                <div id="deny-permissions"></div>
            </div>
        </div>

	</div>
	<div class="box-footer text-right">
		<?= Html::submitButton($model->isNewRecord? 'Save' : 'Update', ['class' => 'btn btn-success']) ?>
        <?= Html::a('delete', ['delete-role', 'name' => $model->name], ['class' => 'delete', 'data-method' => 'post']) ?>
	</div>

	<?php ActiveForm::end(); ?>
</div>
