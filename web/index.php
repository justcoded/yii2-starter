<?php

// comment out the following two lines when deployed to production
defined('YII_DEBUG') or define('YII_DEBUG', true);
defined('YII_ENV') or define('YII_ENV', 'dev');

require(__DIR__ . '/../vendor/autoload.php');
require(__DIR__ . '/../app/web/Yii.php');
require(__DIR__ . '/../app/web/Application.php');
require(__DIR__ . '/../app/bootstrap.php');

$config = require(__DIR__ . '/../config/web.php');

(new \app\web\Application($config))->run();
