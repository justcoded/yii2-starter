<?php

use app\models\User;
use app\modules\admin\widgets\LinkedColumn;
use yii\helpers\Html;
use app\modules\admin\widgets\BoxGridView;
use app\modules\admin\Module as AdminModule;

/* @var $this yii\web\View */
/* @var $searchModel app\modules\admin\models\UserSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title                   = 'Users';
$this->params['breadcrumbs'][] = $this->title;
?>
<?php $this->beginBlock('content-title'); ?>
	<?= Html::a('Add New', ['create'], ['class' => 'btn btn-sm btn-success']) ?>
<?php $this->endBlock(); ?>

<div class="user-index">

	<?= BoxGridView::widget([
		'dataProvider' => $dataProvider,
		'filterModel'  => $searchModel,
		'columns'      => [
			['class' => 'yii\grid\SerialColumn'],
			[
				'class' => LinkedColumn::class,
				'header' => 'Name',
				'attribute' => 'full_name',
				'value' => 'fullName',
			],
			'username',
			'email',
			array(
				'attribute' => 'status',
				'value' => 'statusAlias',
				'filter' => Html::activeDropDownList(
					$searchModel,
					'status',
					User::getStatusesList(),
					['prompt' => 'All', 'class' => 'form-control']
				),
			),
			'created_at:date:Registered',
			// 'updated_at',
		],
	]); ?>

</div>