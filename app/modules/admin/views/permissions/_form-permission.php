<?php

use app\models\User;
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use kartik\select2\Select2;
use yii\helpers\Url;
?>

<?php $form = ActiveForm::begin([
        'layout' => 'horizontal',
]); ?>

<div class="row">
    <div class="col-md-7">
        <div class="box">
            <div class="box-header">
                <h4>Permission Details</h4>
            </div>
            <div class="box-body">
                <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>

                <?= $form->field($model, 'description')->textInput(['maxlength' => true]) ?>

                <?= $form->field($model, 'rule_name')->textInput() ?>
               <div class="col-sm-offset-3 col-sm-p">
                   <p>
                       full class name with namespace <br>
                       more details
                       <a href="<?= Url::to('http://www.yiiframework.com/doc-2.0/guide-security-authorization.html#using-rules') ?>" target="_blank">
                           http://www.yiiframework.com/doc-2.0/guide-security-authorization.html#using-rules
                       </a>
                   </p>
               </div>

            </div>
            <div class="box-footer text-right">
		        <?= Html::submitButton($model->isNewRecord? 'Save' : 'Update', ['class' => 'btn btn-success pull-left']) ?>
            </div>
        </div>
    </div>
    <div class="col-md-5">
        <div class="box">
            <div class="box-header">
                <h4>Roles</h4>
            </div>
            <div class="box-body">
                <?= $form->field($model, 'parent_roles')->textInput(['maxlength' => true])->label(false) ?>
                <div class="col-md-8">
	                <?= Select2::widget([
		                'model' => $model,
		                'attribute' => 'parent_roles_search',
		                'data' => $model->rolesList,
		                'options' => ['placeholder' => 'Search roles ...'],
		                'pluginOptions' => [
			                'allowClear' => true
		                ],
	                ]);?>
                </div>
                <div class="col-md-4">
                    <?= Html::button('Add', [
                        'class' => 'btn btn-md btn-default',
                        'style' => 'width:100%',
                        'id' => 'parent_roles_search'
                    ]) ?>
                </div>
                <div id="parent_roles_list"></div>
            </div>
        </div>
        <div class="box">
            <div class="box-header">
                <h4>Parents</h4>
            </div>
            <div class="box-body">
			    <?= $form->field($model, 'parent_permissions')->textInput(['maxlength' => true]) ?>
                <div class="col-md-8">
		            <?= Select2::widget([
			            'model' => $model,
			            'attribute' => 'parent_permissions_search',
			            'data' => $model->permissionsList,
			            'options' => ['placeholder' => 'Search permissions ...'],
			            'pluginOptions' => [
				            'allowClear' => true
			            ],
		            ]);?>
                </div>
                <div class="col-md-4">
		            <?= Html::button('Add', [
			            'class' => 'btn btn-md btn-default',
			            'style' => 'width:100%',
			            'id' => 'parent_permissions_search'
		            ]) ?>
                </div>
                <div id="parent_permissions_list"></div>
            </div>
        </div>
        <div class="box">
            <div class="box-header">
                <h4>Children</h4>
            </div>
            <div class="box-body">
                <?= $form->field($model, 'children_permissions')->textInput(['maxlength' => true]) ?>
                <div class="col-md-8">
                    <?= Select2::widget([
                        'model' => $model,
                        'attribute' => 'children_permissions_search',
                        'data' => $model->permissionsList,
                        'options' => ['placeholder' => 'Search permissions ...'],
                        'pluginOptions' => [
                            'allowClear' => true
                        ],
                    ]);?>
                </div>
                <div class="col-md-4">
                    <?= Html::button('Add', [
                        'class' => 'btn btn-md btn-default',
                        'style' => 'width:100%',
                        'id' => 'children_permissions_search'
                    ]) ?>
                </div>
                <div id="children_permissions_list"></div>
            </div>
        </div>
    </div>
</div>

<?php ActiveForm::end(); ?>

<?php
$js = <<<JS
jQuery(document).on('click', '#parent_roles_search', function() {
  var title = jQuery('#select2-permissionform-parent_roles_search-container').attr('title')
 jQuery('#parent_roles_list').append(divWrapper(title));
});
jQuery(document).on('click', '#parent_permissions_search', function() {
  var title = jQuery('#select2-permissionform-parent_permissions_search-container').attr('title')
 jQuery('#parent_permissions_list').append(divWrapper(title));
});
jQuery(document).on('click', '#children_permissions_search', function() {
  var title = jQuery('#select2-permissionform-children_permissions_search-container').attr('title')
 jQuery('#children_permissions_list').append(divWrapper(title));
});
jQuery(document).on('click', '', function() {
   var dataListRoles = jQuery("#parent_roles_list > .alert").map(function() {
    return $(this).data("name");
  }).get();
   var dataListPermissions = jQuery("#parent_permissions_list > .alert").map(function() {
    return $(this).data("name");
  }).get();
   var dataListChildrePermissions = jQuery("#children_permissions_list > .alert").map(function() {
    return $(this).data("name");
  }).get();
  jQuery('#permissionform-parent_roles').val(dataListRoles);
  jQuery('#permissionform-parent_permissions').val(dataListPermissions);
  jQuery('#permissionform-children_permissions').val(dataListChildrePermissions);
});
function divWrapper(title) {
 return '<div class="alert" data-name="'+ title + '">'+
  '<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>' + 
  title + '</div>';
}
JS;

$this->registerJs($js, $this::POS_END);
?>