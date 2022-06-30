<?php
$config = array();
$config['redis_config'] = array(
	'cxt_old'=>array(
		'prefix' => 'stocksir:',
		'type'=>'redis',
		'master'=>array(array('host'=>getenv('redis.host'),'port'=>getenv('redis.port'),'pconnect'=>getenv('redis.pconnect'),'db'=>getenv('redis.db'))),
		'slave'=>array(array('host'=>getenv('redis.host'),'port'=>getenv('redis.port'),'pconnect'=>getenv('redis.pconnect'),'db'=>getenv('redis.db')))
	),
);
return $config;