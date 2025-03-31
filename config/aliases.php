<?php

$alises = [
	'@uploads' => dirname(dirname(__DIR__)) . '/../real/web/protected/uploads',
	'@backups' => dirname(dirname(__DIR__)) . '/../real/web/protected/backups',
    '@export' => dirname(dirname(__DIR__)) . '/../real/web/protected/exports',
    '@defaultimg' => dirname(dirname(__DIR__)) . '/real/web/default',
    
];

foreach ($alises as $key => $value) {
	\Yii::setAlias($key, $value);
}
