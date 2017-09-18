<?php

use app\models\User;
use app\modules\admin\widgets\LinkedColumnPermissions;
use yii\helpers\Html;
use app\modules\admin\assets\ThemeHelper;
use app\modules\admin\widgets\BoxGridViewPermissions;

/* @var $this yii\web\View */
/* @var $searchModel app\modules\admin\models\UserSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title                   = 'Permissions';
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="permission-index">
    <div class="row">
        <div class="col-md-6">
            <div class="grid-view box">
                <div class="box-header">
                    <div class="col-xs-6 col-md-2">
                        <h4>Roles</h4>
                    </div>
                    <div class="col-xs-offset-6 col-md-offset-2">
                        <?=  Html::a('Add Role', ['add-role'], ['class' => 'btn btn-sm btn-success']); ?>
                    </div>
                </div>
            </div>
	        <?= BoxGridViewPermissions::widget([
		        'dataProvider' => $dataProviderRoles,
		        'filterModel'  => $searchModelRoles,
		        'columns'      => [
			        ['class' => 'yii\grid\SerialColumn'],
			        [
				        'class' => LinkedColumnPermissions::class,
				        'header' => 'Role',
				        'attribute' => 'name',
				        'format' => 'raw',
				        'value' => function ($data){
					        return $data->name . '</a><br>' . $data->description;
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
	                        return $data->getParent($data->name);
                        },
			        ]
		        ],
	        ]); ?>
        </div>
        <div class="col-md-6">
            <div class="grid-view box">
                <div class="box-header">
                    <div class="col-xs-12 col-sm-6 col-md-3">
                        <h4>Permissions</h4>
                    </div>
                    <div class="col-sm-offset-6 col-md-offset-3">
	                    <?=  Html::a('Scan Routes', ['scan-routes'], ['class' => 'btn btn-sm btn-success']); ?>
			            <?=  Html::a('Add Permissions', ['add-permission'], ['class' => 'btn btn-sm btn-default']); ?>
                    </div>
                </div>
            </div>
	        <?= BoxGridViewPermissions::widget([
		        'dataProvider' => $dataProviderPermissions,
		        'filterModel'  => $searchModelRoles,
		        'columns'      => [
			        ['class' => 'yii\grid\SerialColumn'],
			        [
				        'class' => LinkedColumnPermissions::class,
				        'header' => 'Permissions',
				        'attribute' => 'permission',
				        'value' => function ($data) {
	                        return $data->name;
                        }
			        ],
                    'description',
//			        [
//				        'header' => 'Descriptions',
//				        'attribute' => '',
//				        'value' => 'description'
//			        ],
			        [
				        'header' => 'Role'
			        ]
		        ],
	        ]); ?>

        </div>
    </div>


</div>