<?php
$config = array();

$config['queue_pool']['master']['host']		= '192.168.10.231';
$config['queue_pool']['master']['port']		= 6379;
$config['queue_pool']['master']['db']		= 4;
$config['queue_pool']['master']['pconnect'] = 1;

return $config;