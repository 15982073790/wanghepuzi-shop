<?php
$config = array();
$config['redis_config'] = array(
    'ln'=>array(
        'prefix' => getenv('default_redis_prefix'),
        'type'=>'redis',
        'master'=>array(array('host'=>getenv('default_redis_host'),'port'=>getenv('default_redis_port'),'pconnect'=>getenv('default_redis_pconnect'),'db'=>getenv('default_redis_db'))),
        'slave'=>array(array('host'=>getenv('default_redis_host'),'port'=>getenv('default_redis_port'),'pconnect'=>getenv('default_redis_pconnect'),'db'=>getenv('default_redis_db')))
    )
);
return $config;