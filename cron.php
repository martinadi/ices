<?php
defined('YII_DEBUG') or define('YII_DEBUG',true);
define('USERNAME', 'SYSTEM');

$yii=dirname(__FILE__).'/../yii-1.1.13/framework/yii.php';

//$yii=dirname(__FILE__).'/../yii/framework/yii.php';
$config=dirname(__FILE__).'/protected/config/console.php';

// including Yii
require_once($yii);

// creating and running console application
Yii::createConsoleApplication($config)->run();