<?php
$config = array();

$config['ftp']['default'] = [
	'server' => '192.168.10.231',
	'username' =>  getenv('vendor_ftp_username'),
	'password' => getenv('vendor_ftp_password'),
	'port' => '21',
	'url'=>'http://static.guxiansheng.cn'
	];
return $config;