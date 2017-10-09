<?php

use yii\helpers\Html;
use yii\grid\GridView;
use justcoded\yii2\rbac\assets\RbacAssetBundle;
use justcoded\yii2\rbac\models\ItemSearch;
use yii\helpers\ArrayHelper;
use justcoded\yii2\rbac\forms\RoleForm;

RbacAssetBundle::register($this);

/* @var $this yii\web\View */
/* @var $searchModel justcoded\yii2\rbac\models\ItemSearch */
/* @var $dataProviderPermissions yii\data\ActiveDataProvider */
/* @var $dataProviderRoles yii\data\ActiveDataProvider */

$this->title                   = 'Permissions';
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="permission-index">
    <div class="panel box">
        <div class="panel-header box-header">
            <div class="row">
                <div class="col-md-6">
                    <div class="row">
                        <div class="col-xs-6 col-md-2">
                            <h4>Roles</h4>
                        </div>
                        <div class="col-xs-offset-6 col-md-offset-2">
                            <?=  Html::a('Add Role', ['roles/create'], ['class' => 'btn btn-sm btn-success']); ?>
                        </div>
                    </div>
                    <?= GridView::widget([
                        'dataProvider' => $dataProviderRoles,
                        'filterModel'  => $searchModel,
                        'columns'      => [
                            ['class' => 'yii\grid\SerialColumn'],
                            [
                                'header' => 'Role',
                                'attribute' => 'name',
                                'format' => 'raw',
                                'filter' => Html::textInput(
                                        'role',
                                        Yii::$app->request->get("role"),
                                        ['class' => 'form-control']
                                ),
                                'value' => function ($data){
                                    return Html::a($data->name, ['roles/update', 'name' => $data->name])
                                        . '<br>' . $data->description;
                                },
                            ],
                            [
                                'header' => 'Permission',
                                'value' => function ($data){
                                    return count(Yii::$app->authManager->getPermissionsByRole($data->name));
                                },
                            ],
                            [
                                'header' => 'Inherit',
                                'value' => function ($data){
                                    return ItemSearch::getInherit($data->name);
                                },
                            ]
                        ],
                    ]); ?>
                </div>
                <div class="col-md-6">
                    <div class="row">
                        <div class="col-xs-12 col-sm-6 col-md-3">
                            <h4>Permissions</h4>
                        </div>
                        <div class="col-sm-offset-6 col-md-offset-3">
                            <?=  Html::a('Scan Routes', ['scan/'], ['class' => 'btn btn-sm btn-success']); ?>
                            <?=  Html::a('Add Permission', ['permissions/create'], ['class' => 'btn btn-sm btn-default']); ?>
                        </div>
                    </div>
                    <?= GridView::widget([
                        'dataProvider' => $dataProviderPermissions,
                        'filterModel'  => $searchModel,
                        'columns'      => [
                            ['class' => 'yii\grid\SerialColumn'],
                            [
                                'header' => 'Permissions',
                                'attribute' => 'permission',
                                'format' => 'html',
                                'filter' => Html::textInput(
                                        'permission',
                                        Yii::$app->request->get('permission'),
                                        ['class' => 'form-control']
                                ),
                                'value' => function ($data) {
                                    return Html::a($data->name, ['permissions/update', 'name' => $data->name]);
                                }
                            ],
                            [
                                'attribute' => 'description',
                                'format' => 'html',
                                'filter' => Html::dropDownList('roles',
                                    Yii::$app->request->get('roles'),
	                                ArrayHelper::merge(['' => 'All'],RoleForm::getRolesList()),
                                    ['class' => 'form-control']
                                ),
                            ],
                            [
                                'header' => 'Role',
                                'format' => 'html',
                                'value' => function ($data) {
                                    return isset(ItemSearch::getRoleByPermission()[$data->name]) ?
                                        ItemSearch::getRoleByPermission()[$data->name] : '';
                                }
                            ]
                        ],
                    ]); ?>
                </div>
            </div>
        </div>
    </div>
</div>