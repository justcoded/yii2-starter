<?php

use yii\helpers\Html;
use justcoded\yii2\rbac\widgets\RbacGridView;

/* @var $this yii\web\View */
/* @var $searchModelRoles justcoded\yii2\rbac\models\AuthItemSearch */
/* @var $dataProviderPermissions yii\data\ActiveDataProvider */
/* @var $dataProviderRoles yii\data\ActiveDataProvider */

$this->title                   = 'Permissions';
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="permission-index">
    <div class="row">
        <div class="col-md-6">
            <div class="panel box">
                <div class="panel-header box-header">
                    <div class="col-xs-6 col-md-2">
                        <h4>Roles</h4>
                    </div>
                    <div class="col-xs-offset-6 col-md-offset-2">
                        <?=  Html::a('Add Role', ['roles/create'], ['class' => 'btn btn-sm btn-success']); ?>
                    </div>
                </div>
                <div class="panel-body box-body">
                    <?= RbacGridView::widget([
                        'dataProvider' => $dataProviderRoles,
                        'filterModel'  => $searchModelRoles,
                        'columns'      => [
                            ['class' => 'yii\grid\SerialColumn'],
                            [
                                'header' => 'Role',
                                'attribute' => 'name',
                                'format' => 'raw',
                                'value' => function ($data){
                                    return Html::a($data->name, ['roles/update', 'name' => $data->name])
                                        . '<br>' . $data->description;
                                },
                            ],
                            [
                                'header' => 'Permission',
                                'value' => function ($data){
                                    return $data->countPermissionsByRole($data->name);
                                },
                            ],
                            [
                                'header' => 'Inherit',
                                'value' => function ($data){
                                    return $data->getInherit($data->name);
                                },
                            ]
                        ],
                    ]); ?>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="panel box">
                <div class="panel-header box-header">
                    <div class="col-xs-12 col-sm-6 col-md-3">
                        <h4>Permissions</h4>
                    </div>
                    <div class="col-sm-offset-6 col-md-offset-3">
	                    <?=  Html::a('Scan Routes', ['scan-routes'], ['class' => 'btn btn-sm btn-success']); ?>
			            <?=  Html::a('Add Permission', ['permissions/create'], ['class' => 'btn btn-sm btn-default']); ?>
                    </div>
                </div>
                <div class="panel-body box-body">
                    <?= RbacGridView::widget([
                        'dataProvider' => $dataProviderPermissions,
                        'filterModel'  => $searchModelRoles,
                        'columns'      => [
                            ['class' => 'yii\grid\SerialColumn'],
                            [
                                'header' => 'Permissions',
                                'attribute' => 'permission',
                                'format' => 'html',
                                'value' => function ($data) {
                                    return Html::a($data->name, ['permissions/update', 'name' => $data->name]);
                                }
                            ],
                            'description',
                            [
                                'header' => 'Role',
                                'format' => 'html',
                                'value' => function ($data) {
	                                return isset($data::getRoleByPermission()[$data->name]) ?
                                        $data::getRoleByPermission()[$data->name] : '';
                                }
                            ]
                        ],
                    ]); ?>
                </div>
            </div>
        </div>
    </div>
</div>