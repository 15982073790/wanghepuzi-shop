<?php

$config = array();
$config["static_url"] = getenv('config.static_url');
$config["apidata"] = [["data_id" => "1", "name" => "可见解密电话", "is_have" => 0], ["data_id" => "2", "name" => "可导出解密电话", "is_have" => 0]];
$config['path_prefix'] = getenv('config.path_prefix');
//$config['path_prefix'] = '/data/logs/_data_www_cxt';
$config['rpcapipath'] = [
    getenv('config.rpcapipath'),
];
$config['libpath'] = getenv('config.libpath');
$config['sdkpath'] = getenv('config.sdkpath');
$config['projectpath'] = getenv('config.projectpath');
$config['serviceSite'] = [
        "gateway" => [
            "menuName" => "权限系统",
        ]
    ];
return $config;