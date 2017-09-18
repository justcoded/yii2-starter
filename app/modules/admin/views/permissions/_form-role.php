<?php

use app\models\User;
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use kartik\select2\Select2;

?>

<div class="role-form box">

	<?php $form = ActiveForm::begin([
			'layout' => 'horizontal',
	]); ?>

	<div class="box-body">
		<?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>

		<?= $form->field($model, 'description')->textInput(['maxlength' => true]) ?>

		<?= $form->field($model, 'roles')->checkboxList($model->rolesList)->label('Inherit permissions') ?>

        <?= $form->field($model, 'allow_permissions')->textarea()->label('Will be hidden'); ?>
        <div class="row">
            <div class="col-md-offset-2 col-md-4">
                <?= Html::label($model->getAttributeLabel('allow_permissions')) ?>
                <div id="allow-permissions">

                </div>
            </div>
            <div class="col-md-4">
		        <?= Html::label($model->getAttributeLabel('deny_permissions')) ?>
                <div id="deny-permissions">

                </div>
            </div>
        </div>

	</div>
	<div class="box-footer text-right">
		<?= Html::submitButton($model->isNewRecord? 'Save' : 'Update', ['class' => 'btn btn-success pull-left']) ?>
	</div>

	<?php ActiveForm::end(); ?>
</div>

<?php
$url = \yii\helpers\Url::to(['ajax-permissions']);
$js = <<<JS
jQuery(document).on('click', '#allow-permissions > .permissions', function() {
  jQuery('#deny-permissions').append(jQuery(this));
});
jQuery(document).on('click', '#deny-permissions > .permissions', function() {
  jQuery('#allow-permissions').append(jQuery(this));
});
jQuery(document).on('click', '.permissions', function() {
  var dataList = jQuery("#allow-permissions > .permissions").map(function() {
    return $(this).data("name");
  }).get();
  jQuery('#roleform-allow_permissions').val(dataList);
  console.log(dataList);
})



jQuery(document).ready(function() {
  jQuery.ajax({
    url:'$url',
    type: 'POST',
    data: 'role=',
    success: function (data) {
      jQuery('#allow-permissions').html(data);
    }
  });
});
JS;

$this->registerJs($js, $this::POS_END);
?>