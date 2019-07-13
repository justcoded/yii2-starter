<?php
return [
	'definitions' => [
		'yii\grid\SerialColumn' => [
			'headerOptions' => [
				'style' => 'width:10px;',
			],
		],
		'justcoded\yii2\rbac\widgets\RbacGridView' => [
			'class' => \app\modules\admin\widgets\BoxGridView::class,
		],
		'justcoded\yii2\rbac\widgets\RbacActiveForm' => [
			'class' => \yii\bootstrap4\ActiveForm::class,
		],
	],
];