<?php
$config = array();

$config['db']['ngxlog_tj'] = array('dbhost'=>'192.168.10.210','dbport'=>'3306','dbuser'=>'root','dbpwd'=>'123456!@#','dbname'=>'ngxlog','dbcharset'=>'UTF-8');

//手机加密开关
$config['mobile_enc'] = true;

//appkey与appcode配置
$config['gxs_token']['app_code'] = '5b6ab2f42cc71c9mqblxxgj1'; //服务平台鉴权code
$config['gxs_token']['app_key'] = '5b6ab2f42cccefjv3gwy2vn1';//服务平台鉴权key
$config['cxt_servicestoken']['app_code']                  = '5af3f79712495uhuzp200rsr';
$config['cxt_servicestoken']['app_key']                   = '5af3f797124e4ehxuw0g279n';

$config['gx_token']['app_code']                  = '5b06244a138da3ri17jrhkky';
$config['gx_token']['app_key']                   = '5b06244a139260346vxj1clz';
$config['gx_white']=[1,2,3];//允许访问股信的用户id(一维数组形式)，当等于‘ALL’的时候允许所有人访问


return $config;