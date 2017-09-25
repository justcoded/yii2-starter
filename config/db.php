<?php

dotenv()->required([
	'DB_HOST',
	'DB_NAME',
	'DB_USER',
	'DB_PASS',
])->notEmpty();

$dsn = env('DB_DSN', 'mysql:host={host};dbname={dbname}');

return [
	'class'    => 'yii\db\Connection',
	'dsn'      => strtr($dsn, [
		'{host}' => env('DB_HOST'),
	    '{dbname}' => env('DB_NAME'),
	]),
	'username' => env('DB_USER'),
	'password' => env('DB_PASS'),
	'charset'  => 'utf8',

	// Schema cache options (default 'off' for dev env)
	'schemaCache' => env('DB_SCHEMA_CACHE', false), // use 'cache' for production
	'enableSchemaCache' => false !== env('DB_SCHEMA_CACHE', false),
	'schemaCacheDuration' => env('DB_SCHEMA_CACHE_DURATION', 60),
];
