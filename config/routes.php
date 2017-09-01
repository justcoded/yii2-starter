<?php

return [
	'/' => 'site/index',
	'login' => 'site/login',
	'password/request' => 'site/password-request',
	'password/update' => 'site/password-update',
	'logout' => 'site/logout',

	'<controller>/<id:\d>/<action>' => '<controller>/<action>',
	'<controller>/<action>' => '<controller>/<action>',
];