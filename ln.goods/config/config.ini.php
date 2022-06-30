<?php

$config['xcx_appid']  = getenv('xcx_appid');//小程序appid
$config['xcx_mchid']  = getenv('xcx_mchid');//小程序appid
$config['xcx_key']    = getenv('xcx_key');//小程序秘钥
$config['xcx_secret'] = getenv('xcx_secret');//小程序秘钥

$config['ebusiness_id'] = getenv('ebusiness_id');//快递鸟用户ID
$config['app_key']      = getenv('app_key');//快递鸟appKey

$config['notify_url']      = getenv('notify_url');//支付回调地址

$config['shipper_code'] = [
    'zhongtongkuaiyun' => '中通快运',
    'ems'              => 'EMS',
];

return $config;