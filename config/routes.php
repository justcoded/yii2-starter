<?php

return [
	'/' => 'site/index',
	'login' => 'auth/login',
	'password/forgot' => 'auth/password-request',
	'password/update' => 'auth/password-update',
	'logout' => 'site/logout',

	'<controller>/<id:\d>/<action>' => '<controller>/<action>',
	'<controller>/<action>' => '<controller>/<action>',
];