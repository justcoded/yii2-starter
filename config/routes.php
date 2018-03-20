<?php

return [
	'/'     => 'site/index',
	'login' => 'auth/login',
	'register'        => 'auth/register',
	'password/forgot' => 'auth/password-request',
	'password/update/<token:\w+>' => 'auth/password-update',
	'logout' => 'site/logout',

	'<controller>/<id:\d>/<action>' => '<controller>/<action>',
	'<controller>/<action>' => '<controller>/<action>',

	//'admin/permissions/<action>' => 'admin/rbac/permissions/<action>',

];