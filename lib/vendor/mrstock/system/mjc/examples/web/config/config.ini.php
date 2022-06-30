<?php

$config = array();

$config['route']['module']                  = "AC";
//压缩类型
$config['gip'] 							= 1;
$config['cxt_key'] 					    = '1ana&$^^@(!(!@_#)!#anans`~sssdds21';

//数据库配置
$config['dbdriver'] 					= 'mysqli';
$config['tablepre']						= 'stock_';
$config['db']['1']['dbhost']			= '192.168.10.230';
$config['db']['1']['dbport']			= '3306';
$config['db']['1']['dbuser']			= 'stocksir';
$config['db']['1']['dbpwd']				= 'stocksir1704!';
$config['db']['1']['dbname']			= 'stocksir';
$config['db']['1']['dbcharset']			= 'UTF-8';
$config['db']['1']['dbtablepre']		= 'stock_';

$config['db']['2']['dbhost']			= '192.168.10.230';
$config['db']['2']['dbport']			= '3306';
$config['db']['2']['dbuser']			= 'stocksir';
$config['db']['2']['dbpwd']				= 'stocksir1704!';
$config['db']['2']['dbname']			= 'stocksir';
$config['db']['2']['dbcharset']			= 'UTF-8';
$config['db']['2']['dbtablepre']		= 'stock_';

$config['db']['master']					= "1";
$config['db']['slave']					= "2";


$config['agency_login_key'] 						= 'gxsAppAgencyLoginKey_2016@!';
$config['agency_login_url'] 						= 'http://www.apitps.com/';

//服务平台
$config['service_app_code'] = '5af3f79712495uhuzp200rsr';
$config['service_app_key'] = '5af3f797124e4ehxuw0g279n';

return $config;