<?php
/* @var $this \yii\web\View */
/* @var $model \justcoded\yii2\rbac\forms\RoleForm */
/* @var $form \yii\bootstrap\ActiveForm */

use yii\helpers\Html;

$allowTree = $model->getLinearTree($model->allowPermissions);
$denyTree = $model->getLinearTree($model->denyPermissions);
$inheritTree = $model->getLinearTree($model->inheritPermissions);

// TODO: update to tree structured dual list panel
?>
<div class="row">
	<div class="col-sm-5">
		<h4>Allowed Permissions</h4>
		<select name="<?= $model->formName() ?>[allowPermissions][]" id="allow_permissions" class="form-control" size="16" multiple="multiple">
			<?= $this->render('_permission-options', ['treeItems' => $allowTree]); ?>

			<?php if (!empty($inheritTree)) : ?>
			<optgroup label="Inherit Permissions" data-weight="<?= end($inheritTree)['order']; ?>">
				<?= $this->render('_permission-options', ['treeItems' => $inheritTree]); ?>
			</optgroup>
			<?php endif; ?>
		</select>
	</div>

	<div class="col-sm-2">
		<h4>&nbsp;</h4>
		<button type="button" id="allow_permissions_rightAll" class="btn btn-block"><i class="glyphicon glyphicon-forward"></i></button>
		<button type="button" id="allow_permissions_rightSelected" class="btn btn-block"><i class="glyphicon glyphicon-chevron-right"></i></button>
		<button type="button" id="allow_permissions_leftSelected" class="btn btn-block"><i class="glyphicon glyphicon-chevron-left"></i></button>
		<button type="button" id="allow_permissions_leftAll" class="btn btn-block"><i class="glyphicon glyphicon-backward"></i></button>
	</div>

	<div class="col-sm-5">
		<h4>Denied Permissions</h4>
		<select id="deny_permissions" class="form-control" size="16" multiple="multiple">
			<?= $this->render('_permission-options', ['treeItems' => $denyTree]); ?>
		</select>
	</div>
</div>