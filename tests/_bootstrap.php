<?php

require(__DIR__ . '/../vendor/autoload.php');
require(__DIR__ . '/../app/bootstrap.php');
// support .env file
dotenv(dirname(__DIR__))->load();

define('YII_ENV', 'test');
defined('YII_DEBUG') or define('YII_DEBUG', true);

require_once(__DIR__ . '/../vendor/yiisoft/yii2/Yii.php');
