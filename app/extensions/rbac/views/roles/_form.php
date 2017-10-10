<?php
/* @var $this \yii\web\View */
/* @var $model \justcoded\yii2\rbac\forms\RoleForm */
/* @var $role \justcoded\yii2\rbac\models\Role */

use justcoded\yii2\rbac\models\Role;
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use justcoded\yii2\rbac\forms\ItemForm;
use justcoded\yii2\rbac\assets\RbacAssetBundle;

RbacAssetBundle::register($this);
?>

<?php $form = ActiveForm::begin(); ?>
<div id="justcoded-role-form" class="role-form panel box">
	<div class="panel-header box-header with-border">
		<h3 class="box-title">Role</h3>
	</div>
	<div class="panel-body box-body">
		<?= $form->field($model, 'name')->textInput([
			'maxlength' => true,
			'readonly'  => $model->scenario != ItemForm::SCENARIO_CREATE
		]) ?>

		<?= $form->field($model, 'description')->textInput(['maxlength' => true]) ?>

		<?= $form->field($model, 'childRoles')
				->inline()
				->checkboxList(array_diff(Role::getList(), [$model->name]), [
					'value' => $model->childRoles,
				])
		?>

		<?= $this->render('_permissions', [
				'model' => $model,
				'form'  => $form,
		]) ?>
	</div>
	<div class="panel-footer box-footer text-right">
		<?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?> &nbsp;
		<?php if (!empty($role)) : ?>
			<?= Html::a(
				'delete',
				['delete', 'name' => $model->name],
				[
					'class' => 'text-danger',
					'data'  => [
						'confirm' => 'Are you sure you want to delete this item?',
						'method'  => 'post',
					],
				]
			) ?>
		<?php endif; ?>
	</div>

</div>
<?php ActiveForm::end(); ?>
