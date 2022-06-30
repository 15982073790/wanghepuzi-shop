<?php
$v = basename($_SERVER['argv'][0]);
$v=str_replace('.php', "", $v);

$_REQUEST['c'] = $_SERVER['argv'][1];
$_REQUEST['a'] = $_SERVER['argv'][2];
$_REQUEST['v'] = $v;
$_REQUEST['site'] = $_SERVER['argv'][3];
$_REQUEST['appcode'] = getenv("vendor_appcode");
$_REQUEST['queue_key'] = $_SERVER['argv'][4];
$_REQUEST['serviceversion'] = $_SERVER['argv'][5];
?>
