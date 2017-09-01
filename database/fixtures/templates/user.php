<?php
/**
 * @var $faker \Faker\Generator
 * @var $index integer
 */

$email = $faker->email;
return [
	'first_name' => $faker->firstName,
	'last_name' => $faker->lastName,
	'username' => $email,
	'email' => $email,
	'password_hash' => Yii::$app->getSecurity()->generatePasswordHash('password_' . $index),
	'auth_key' => Yii::$app->getSecurity()->generateRandomString(),
	'status' => 10,
];