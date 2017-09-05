<?php
return [
	'container' => [
		'definitions' => [
			'yii\grid\SerialColumn' => [
				'headerOptions' => [
					'style' => 'width:10px;',
				],
			],
		],
	],
	'components' => [
		'errorHandler' => [
			'errorAction' => 'admin/dashboard/error',
		],
	],
];