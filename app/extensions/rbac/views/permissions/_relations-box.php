<?php
/* @var $model \justcoded\yii2\rbac\forms\PermissionForm */
/* @var $relModel \justcoded\yii2\rbac\forms\PermissionRelForm */
/* @var $title string */
/* @var $introMsg string */
/* @var $emptyMsg string */
/* @var $searchMsg string */
/* @var $btnTxt string */
/* @var $options array */
/* @var $selected array */

use kartik\select2\Select2;
use yii\bootstrap\ActiveForm;
use yii\bootstrap\Html;

?>

<?php $form = ActiveForm::begin([
	'action' => ['add-relation', 'name' => $model->name],
]); ?>

<div class="panel box">
	<div class="panel-header box-header with-border">
		<h3 class="box-title"><?= $title ?></h3>
	</div>
	<div class="panel-body box-body">
		<?php if ($selected) : ?>
			<p><?= $introMsg ?></p>
			<table class="table table-striped">
				<tbody>
				<?php foreach ($selected as $item) : ?>
					<tr>
						<td>
							<?= Html::encode($item->name); ?>
						</td>
						<td><small><?= Html::encode($item->description); ?></small></td>
						<td>
							<?php //we can't remove if this item is inherit from some other item ?>
							<?php if (!isset($item->data['_inherit']) || ! $item->data['_inherit']) : ?>
								<?= Html::a(
									'&times;',
									['remove-relation', 'name' => $model->name, 'item' => $item->name, 'scenario' => $relModel->scenario],
									[
										'class' => 'text-danger',
										'data' => [
											'confirm' => 'Are you sure you want to delete this item?',
											'method' => 'post',
										],
									]
								) ?>
							<?php endif; ?>
						</td>
					</tr>
				<?php endforeach; ?>
				</tbody>
			</table>
			<br>
		<?php else : ?>
			<p><strong><?= $emptyMsg ?></strong></p>
		<?php endif; ?>

		<div class="row">
			<div class="col-md-9">
				<?= Select2::widget([
					'model'         => $relModel,
					'attribute'     => 'names',
					'data'          => array_diff($options, array_keys($selected), [$model->name]),
					'options'       => [
						'id' => $form->id . '-search',
						'placeholder' => $searchMsg,
						'multiple' => true,
					],
					'pluginOptions' => [
						'allowClear' => true,
					],
				]); ?>

				<?= $form->field($relModel, 'scenario')->hiddenInput()->label(false); ?>
			</div>
			<div class="col-md-3">
				<?= Html::submitButton($btnTxt, [
					'class' => 'btn btn-default',
				]) ?>
			</div>
		</div>
	</div>
</div>

<?php ActiveForm::end(); ?>
