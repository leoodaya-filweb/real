<?php


defined('YII_DEBUG') or define('YII_DEBUG', true);
defined('YII_ENV') or define('YII_ENV', 'dev');
// defined('APP_ENV') or define('APP_ENV', 'live');


// header('location: /maintenance.html');

//require 'functions.php';

require __DIR__ . '/../vendor/autoload.php';
require __DIR__ . '/../vendor/yiisoft/yii2/Yii.php';

require __DIR__ . '/../../real/config/aliases.php';

$config = require __DIR__ . '/../../real/config/web.php';




(new yii\web\Application($config))->run();
