<?php
$config = array();
use MrStock\System\Helper\Config;
Config::unsetKey('ftp.default');
$config['ftp']['default'] = [
	'server' => getenv('default_ftp_server'),
	'username' => getenv('default_ftp_username'),
	'password' => getenv('default_ftp_password'),
	'port' => getenv('default_ftp_port'),
	'url'=>getenv('default_ftp_url')
	];
return $config;