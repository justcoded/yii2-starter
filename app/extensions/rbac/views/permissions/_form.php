<?php
/* @var $this \yii\web\View */
/* @var $model \justcoded\yii2\rbac\forms\PermissionForm */
/* @var $permission \justcoded\yii2\rbac\models\Permission */
/* @var $relModel PermissionRelForm */

use justcoded\yii2\rbac\forms\PermissionRelForm;
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use kartik\select2\Select2;
use justcoded\yii2\rbac\forms\ItemForm;

?>

<div class="row">
	<div class="col-md-7">
		<?php $form = ActiveForm::begin([
			'id' => 'form-permission',
		]); ?>
		<div class="panel box">
			<div class="panel-header box-header with-border">
				<h3 class="box-title">Permission Details</h3>
			</div>
			<div class="panel-body box-body">

				<?= $form->field($model, 'name')->textInput([
					'maxlength' => true,
					'readonly'  => $model->scenario != ItemForm::SCENARIO_CREATE
				]) ?>

				<?= $form->field($model, 'description')->textInput(['maxlength' => true]) ?>

				<?= $form->field($model, 'ruleClass')->textInput() ?>

			</div>
			<div class="panel-footer box-footer text-right">
				<?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?> &nbsp;
				<?php if (!empty($permission)) : ?>
					<?= Html::a(
						'delete',
						['delete', 'name' => $model->name],
						[
							'class' => 'text-danger',
							'data' => [
								'confirm' => 'Are you sure you want to delete this item?',
								'method' => 'post',
							],
						]
					) ?>
				<?php endif; ?>
			</div>
		</div>
		<?php ActiveForm::end(); ?>

		<?php if (!empty($permission)) : ?>
		<p class="text-center">
			<br>
			<?= Html::a('Add another permission', ['permissions/create'], ['class' => 'btn btn-default bg-gray']); ?>
		</p>
		<?php endif; ?>
	</div>

	<?php if (!empty($permission)) : ?>
	<div class="col-md-5">
		<?php

		$relModel->scenario = PermissionRelForm::SCENARIO_ADDROLE;
		echo $this->render('_relations-box', [
				'title' => 'Asigned Roles',
				'relModel' => $relModel,
				'model' => $model,
				'introMsg' => 'Permission is assigned to such roles:',
				'emptyMsg' => 'Permission is not assigned to any roles.',
				'searchMsg' => 'Search roles...',
				'options' => \justcoded\yii2\rbac\models\Role::getList(),
				'selected' => $permission->getRoles(),
				'btnTxt' => 'Assign',
		]);

		$relModel->scenario = PermissionRelForm::SCENARIO_ADDPARENT;
		echo $this->render('_relations-box', [
				'title' => 'Parents',
				'relModel' => $relModel,
				'model' => $model,
				'introMsg' => 'Permission is a <b>child</b> of such permissions:',
				'emptyMsg' => 'Permission doesn\'t have parents.',
				'searchMsg' => 'Search permissions...',
				'options' => \justcoded\yii2\rbac\models\Permission::getList(),
				'selected' => $permission->getParents(),
				'btnTxt' => 'Add Parents',
		]);

		$relModel->scenario = PermissionRelForm::SCENARIO_ADDCHILD;
		echo $this->render('_relations-box', [
				'title' => 'Children',
				'relModel' => $relModel,
				'model' => $model,
				'introMsg' => 'Permission is a <b>parent</b> of such permissions:',
				'emptyMsg' => 'Permission doesn\'t have children.',
				'searchMsg' => 'Search permissions...',
				'options' => \justcoded\yii2\rbac\models\Permission::getList(),
				'selected' => $permission->getChildren(),
				'btnTxt' => 'Add Childs',
		]); ?>
	<?php endif; ?>
</div>

