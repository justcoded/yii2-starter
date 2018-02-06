<?php

$params = require __DIR__ . '/params.php';

return [
	'class'     => 'app\components\Settings',
	'defaults' => $params,
	'modelsMap' => [
		'app' => 'justcoded\yii2\settings\forms\AppSettingsForm',
	],
];