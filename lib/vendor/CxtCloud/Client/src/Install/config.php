<?php


define( 'VENDOR_DIR', 'D:/Project/service/vendor/trunk/vendor' );
require_once VENDOR_DIR . "/autoload.php";

//sdk dir
$sdkpath            = 'D:/Project/composer/mrstock/cloud/php/sdk/trunk/src/';
$GLOBALS['sdkpath'] = $sdkpath;

//service svn checkout dir
$projectPath = 'D:/Project/service/';
$GLOBALS['projectpath'] = $projectPath;

$svnAccount = "--username zhangjiulin --password 123456";
$GLOBALS['svnaccount'] = $svnAccount;