<?php
$config = array();
$config['redis_hashsharding'] = array('stocksir:questions','stocksir:policys','stocksir:comments','stocksir:bbs','stocksir:member:message','stocksir:member:sms');

$config['redis_config'] = array(
	'appcluster'=>array(
		'prefix' => 'stocksir_',
		'dynamicprefix' => ['site','appcode'],
           'type'=>'clusterclient',
           'seeds_nodes' =>array(
                    '192.168.10.66:7000',
                    '192.168.10.66:7001',
                    '192.168.10.66:7002',
                    '192.168.10.66:7003',
                    '192.168.10.66:7004',
                    '192.168.10.66:7005',
                    '192.168.10.66:7006',
                    '192.168.10.66:7007',
                    '192.168.10.66:7008',
                    '192.168.10.66:7009',
                    '192.168.10.66:7010',
                    '192.168.10.66:7011',
           ),
           
	),
       'imcluster'=>array(
           'prefix' => 'im_',
		   'dynamicprefix' => ['site','appcode'],
           'type'=>'clusterclient',
           'seeds_nodes' =>array(
                   '192.168.10.66:7000',
                    '192.168.10.66:7001',
                    '192.168.10.66:7002',
                    '192.168.10.66:7003',
                    '192.168.10.66:7004',
                    '192.168.10.66:7005',
                    '192.168.10.66:7006',
                    '192.168.10.66:7007',
                    '192.168.10.66:7008',
                    '192.168.10.66:7009',
                    '192.168.10.66:7010',
                    '192.168.10.66:7011',
           ),
       ),
	'hq'=>array(
		'prefix' => 'stocksir_',
		'type'=>'redis',
		'master'=>array(array('host'=>'120.76.103.80','port'=>6679,'pconnect'=>0,'db'=>0)),
	    'slave'=>array(array('host'=>'120.76.103.80','port'=>6679,'pconnect'=>0,'db'=>0))
	),
	'queue'=>array(
		'prefix' => 'QUEUE_',
		'dynamicprefix' => ['site','appcode'],
		'type'=>'redis',
		'master'=>array(array('host'=>'192.168.10.231','port'=>6379,'pconnect'=>0,'db'=>3)),
		'slave'=>array(array('host'=>'192.168.10.231','port'=>6379,'pconnect'=>0,'db'=>3))
	),
  'message'=>array(
    'prefix' => '',
    'dynamicprefix' => ['site','appcode'],
    'type'=>'redis',
    'master'=>array(array('host'=>'192.168.10.231','port'=>6379,'pconnect'=>0,'db'=>3)),
    'slave'=>array(array('host'=>'192.168.10.231','port'=>6379,'pconnect'=>0,'db'=>3))
  ),
);
return $config;