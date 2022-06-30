<?php
$config = array();
$config['redis_hashsharding'] = array('stocksir:questions','stocksir:policys','stocksir:comments','stocksir:bbs','stocksir:member:message','stocksir:member:sms');

$config['redis_config'] = array(
	'cluster'=>array(
		'prefix' => 'stocksir_',
		'type'=>'cluster',
		'seeds_nodes' =>array(  
			'192.168.10.243:7001',
			'192.168.10.243:7001',
			'192.168.10.243:7002',   
		),
		'slaves'=>array(
			'5460'=>array(array('host'=>'192.168.10.243','port'=>7000,'pconnect'=>0,'db'=>0)),
			'10922'=>array(array('host'=>'192.168.10.243','port'=>7001,'pconnect'=>0,'db'=>0)),
			'16383'=>array(array('host'=>'192.168.10.243','port'=>7002,'pconnect'=>0,'db'=>0))
		),
	),
	'appcluster'=>array(
		'prefix' => 'stocksir_',
           'type'=>'clusterclient',
           'seeds_nodes' =>array(
                   '192.168.10.243:7006',
                   '192.168.10.243:7000',
                   '192.168.10.243:7001',
                   '192.168.10.243:7002',
           ),
           'master_groups'=>array(
                   '4084'=>array(array('host'=>'192.168.10.243','port'=>7006,'pconnect'=>0,'db'=>0)),
                   '8169'=>array(array('host'=>'192.168.10.243','port'=>7000,'pconnect'=>0,'db'=>0)),
                   '12254'=>array(array('host'=>'192.168.10.243','port'=>7001,'pconnect'=>0,'db'=>0)),
                   '16383'=>array(array('host'=>'192.168.10.243','port'=>7002,'pconnect'=>0,'db'=>0))
           ),
           'slaves'=>array(
                   '4084'=>array(array('host'=>'192.168.10.243','port'=>7007,'pconnect'=>0,'db'=>0)),
                   '8169'=>array(array('host'=>'192.168.10.243','port'=>7003,'pconnect'=>0,'db'=>0)),
                   '12254'=>array(array('host'=>'192.168.10.243','port'=>7004,'pconnect'=>0,'db'=>0)),
                   '16383'=>array(array('host'=>'192.168.10.243','port'=>7005,'pconnect'=>0,'db'=>0))
           ),
	),
       'imcluster'=>array(
           'prefix' => 'im_',
           'type'=>'clusterclient',
           'seeds_nodes' =>array(
                   '192.168.10.243:7006',
                   '192.168.10.243:7000',
                   '192.168.10.243:7001',
                   '192.168.10.243:7002',
           ),
           'master_groups'=>array(
                   '4084'=>array(array('host'=>'192.168.10.243','port'=>7006,'pconnect'=>0,'db'=>0)),
                   '8169'=>array(array('host'=>'192.168.10.243','port'=>7000,'pconnect'=>0,'db'=>0)),
                   '12254'=>array(array('host'=>'192.168.10.243','port'=>7001,'pconnect'=>0,'db'=>0)),
                   '16383'=>array(array('host'=>'192.168.10.243','port'=>7002,'pconnect'=>0,'db'=>0))
           ),
           'slaves'=>array(
                   '4084'=>array(array('host'=>'192.168.10.243','port'=>7007,'pconnect'=>0,'db'=>0)),
                   '8169'=>array(array('host'=>'192.168.10.243','port'=>7003,'pconnect'=>0,'db'=>0)),
                   '12254'=>array(array('host'=>'192.168.10.243','port'=>7004,'pconnect'=>0,'db'=>0)),
                   '16383'=>array(array('host'=>'192.168.10.243','port'=>7005,'pconnect'=>0,'db'=>0))
           ),
       ),
	'appsentinel'=>array(
		'prefix' => 'stocksir_',
		'type'=>'sentinel',
		'sentinel_servers' =>array(  
			array('192.168.10.243','26379'),
			array('192.168.10.243','26380'),
			array('192.168.10.243','26381'),   
		),
		'cluster_alias' =>array(  
			'16383'=>'master1',
		),
		'slaves'=>array(
			'16383'=>array(array('host'=>'192.168.10.243','port'=>6379,'pconnect'=>1,'db'=>0))
		),
	),
	'hq'=>array(
		'prefix' => 'stocksir_',
		'type'=>'redis',
		'master'=>array(array('host'=>'112.74.132.90','port'=>6479,'pconnect'=>0,'db'=>0)),
	    'slave'=>array(array('host'=>'112.74.132.90','port'=>6479,'pconnect'=>0,'db'=>0))
	),
	'queue'=>array(
		'prefix' => 'stocksir_',
		'type'=>'redis',
		'master'=>array(array('host'=>'192.168.10.231','port'=>6379,'pconnect'=>0,'db'=>3)),
		'slave'=>array(array('host'=>'192.168.10.231','port'=>6379,'pconnect'=>0,'db'=>3))
	),
       'combine'=>array(
           'prefix' => 'stocksir_',
           'type'=>'redis',
           'master'=>array(array('host'=>'192.168.10.231','port'=>6379,'pconnect'=>0,'db'=>0)),
           'slave'=>array(array('host'=>'192.168.10.231','port'=>6379,'pconnect'=>0,'db'=>0),array('host'=>'192.168.10.231','port'=>6379,'pconnect'=>0,'db'=>0))
       ),
	'local'=>array(
		'prefix' => 'stocksir_',
		'type'=>'redis',
		'master'=>array(array('host'=>'192.168.0.222','port'=>6379,'pconnect'=>0,'db'=>0)),
		'slave'=>array(array('host'=>'192.168.0.222','port'=>6379,'pconnect'=>0,'db'=>0))
	),
	'crm'=>array(
           'prefix' => 'stocksir_',
           'type'=>'redis',
           'master'=>array(array('host'=>'192.168.10.243','port'=>6379,'pconnect'=>0,'db'=>0)),
           'slave'=>array(array('host'=>'192.168.10.243','port'=>6379,'pconnect'=>0,'db'=>0))
       ),
	'gxsoa'=>array(
		'prefix' => 'stocksir_',
		'type'=>'redis',
		'master'=>array(array('host'=>'192.168.10.243','port'=>6379,'pconnect'=>0,'db'=>0)),
		'slave'=>array(array('host'=>'192.168.10.243','port'=>6379,'pconnect'=>0,'db'=>0))
	),

);
return $config;