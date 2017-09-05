<?php
/**
 * @var $faker \Faker\Generator
 * @var $index integer
 */

$email = $faker->email;
$created = time() - rand(0, 3600 * 24 * 60); // not more than 2 months old
return [
	'first_name' => $faker->firstName,
	'last_name' => $faker->lastName,
	'username' => $email,
	'email' => $email,
	'password' => 'password_' . $index,
	'password_hash' => Yii::$app->getSecurity()->generatePasswordHash('password_' . $index),
	'auth_key' => Yii::$app->getSecurity()->generateRandomString(),
	'status' => 10,
	'created_at' => $created,
	'updated_at' => time() - rand(0, time() - $created),
];