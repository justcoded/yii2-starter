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

<div class="row">
    <div class="col-md-7">
        <div class="box">
            <div class="box-header">
                <h4>Permission Details</h4>
            </div>
            <div class="box-body height-400">
                <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>

                <?= $form->field($model, 'description')->textInput(['maxlength' => true]) ?>

                <?= $form->field($model, 'ruleName', [
                'template' => "{label}<div class=\"col-sm-6\">{input}</div>\n
                                <div class=\"col-sm-offset-3 col-sm-6\">{error}</div>\n
                                <div class=\"col-sm-offset-3 col-sm-9\">{hint}</div>",
                ])->textInput()->hint(
                        'full class name with namespace <br> more details 
                        <a target="_blank" href="' . Url::to('http://www.yiiframework.com/doc-2.0/guide-security-authorization.html#using-rules').'">
                        http://www.yiiframework.com/doc-2.0/guide-security-authorization.html#using-rules
                        </a>',
                        ['class' => ' ']
                ) ?>
	            <?= $form->field($model, 'parent_roles')
		            ->hiddenInput(['value' => $model->parentRolesString])
		            ->label(false)
	            ?>
	            <?= $form->field($model, 'parent_permissions')
		            ->hiddenInput(['value' => $model->parentPermissionsString])
		            ->label(false)
	            ?>
	            <?= $form->field($model, 'children_permissions')
		            ->hiddenInput(['value' => $model->childrenPermissionsString])
		            ->label(false)
	            ?>
            </div>
            <div class="box-footer text-right">
		        <?= Html::submitButton('Save' , ['class' => 'btn btn-success']) ?>
		        <?= Html::a('delete', ['delete', 'name' => $model->name], ['class' => 'delete', 'data-method' => 'post']) ?>
            </div>
        </div>
    </div>
    <div class="col-md-5">
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
                    <table class="table table-striped">
                        <tbody>
                            <?php if ($model->parentRolesString): ?>
                                <?php foreach (explode(',', $model->parentRolesString) as $role): ?>
                                    <tr>
                                        <td class="alert" data-name="<?=$role ?>">
                                            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                                           <?= $role ?>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
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
                    <table class="table table-striped">
                        <tbody>
                            <?php if ($model->parentPermissionsString): ?>
                                <?php foreach (explode(',', $model->parentPermissionsString) as $permission): ?>
                                    <tr>
                                        <td class="alert" data-name="<?= $permission ?>">
                                            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                                            <?= $permission ?>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
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
                    <table class="table table-striped">
                        <tbody>
                            <?php if ($model->childrenPermissionsString): ?>
                                <?php foreach (explode(',', $model->childrenPermissionsString) as $permission): ?>
                                    <tr>
                                        <td class="alert" data-name="<?= $permission ?>">
                                            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                                            <?= $permission ?>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<?php ActiveForm::end(); ?>
