<?php
$config = [];
$config['container']['bind'] = [
	'app' => 'MrStock\System\MJC\App',
	'filter' => 'MrStock\System\MJC\Filter',
	'hook' => 'MrStock\System\MJC\Hook',
	'router' => 'MrStock\System\MJC\Route\AC',
	'error' => 'MrStock\System\MJC\Error',
	'container' => 'MrStock\System\MJC\Container',
	'request' => 'MrStock\System\MJC\Http\Request',
	'response' => 'MrStock\System\MJC\Http\Response',
    'log' => 'MrStock\System\MJC\Log',
	'redis' => 'MrStock\System\Redis\RedisHelper'
];

return $config;