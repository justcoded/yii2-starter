<?php
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use kartik\select2\Select2;
use yii\helpers\Url;
?>

<?php $form = ActiveForm::begin([
        'id' => 'form-permission',
        'layout' => 'horizontal',
        'enableAjaxValidation' => true,
]); ?>
<?php if(!empty($model->errors)){ pa($model->errors);} ?>
<div class="row">
    <div class="col-md-7">
        <div class="box">
            <div class="box-header">
                <h4>Permission Details</h4>
            </div>
            <div class="box-body height-450">
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
		        <?= Html::submitButton('Save' , ['class' => 'btn btn-success']) ?>
		        <?= Html::a('delete', ['delete-permission', 'name' => $model->name], ['class' => 'delete', 'data-method' => 'post']) ?>
            </div>
        </div>
    </div>
    <div class="col-md-5">

	    <?= $form->field($model, 'parent_roles')
		    ->hiddenInput(['maxlength' => true, 'value' => $model->parentRoles])
		    ->label(false)
	    ?>
        <div class="box">
            <div class="box-header">
                <h4>Roles</h4>
            </div>
            <div class="box-body">
                <div class="row">
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
                            'class' => 'btn btn-md btn-default no-border-radius',
                            'style' => 'width:100%',
                            'id' => 'parent_roles_search'
                        ]) ?>
                    </div>
                </div>
                <div id="parent_roles_list">
                    <?php if ($model->parentRoles): ?>
                        <?php foreach (explode(',', $model->parentRoles) as $role): ?>
                            <div class="alert" data-name="<?=$role ?>">
                                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                               <?= $role ?>
                            </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>
            </div>
        </div>

	    <?= $form->field($model, 'parent_permissions')
		    ->hiddenInput(['maxlength' => true, 'value' => $model->parentPermissions])
		    ->label(false)
	    ?>
        <div class="box">
            <div class="box-header">
                <h4>Parents</h4>
            </div>
            <div class="box-body">
                <div class="row">
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
                            'class' => 'btn btn-md btn-default no-border-radius',
                            'style' => 'width:100%',
                            'id' => 'parent_permissions_search'
                        ]) ?>
                    </div>
                </div>
                <div id="parent_permissions_list">
	                <?php if ($model->parentPermissions): ?>
		                <?php foreach (explode(',', $model->parentPermissions) as $permission): ?>
                            <div class="alert" data-name="<?= $permission ?>">
                                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
				                <?= $permission ?>
                            </div>
		                <?php endforeach; ?>
	                <?php endif; ?>
                </div>
            </div>
        </div>

	    <?= $form->field($model, 'children_permissions')
		    ->hiddenInput(['maxlength' => true, 'value' => $model->childrenPermissions])
		    ->label(false)
	    ?>
        <div class="box">
            <div class="box-header">
                <h4>Children</h4>
            </div>
            <div class="box-body">
                <div class="row">
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
                            'class' => 'btn btn-md btn-default no-border-radius',
                            'style' => 'width:100%',
                            'id' => 'children_permissions_search'
                        ]) ?>
                    </div>
                </div>
                <div id="children_permissions_list">
	                <?php if ($model->childrenPermissions): ?>
		                <?php foreach (explode(',', $model->childrenPermissions) as $permission): ?>
                            <div class="alert" data-name="<?= $permission ?>">
                                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
				                <?= $permission ?>
                            </div>
		                <?php endforeach; ?>
	                <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<?php ActiveForm::end(); ?>
