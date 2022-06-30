<?php
$config = [];

//默认行为
$config['hooks']['application'] = [
    'before_select'      => ['MrStock\Business\ServiceSdk\Hook\BeforeSelect'],
    
    'before_insert'      => ['MrStock\Business\ServiceSdk\Hook\BeforeInsert'],
    
    'before_update'      => ['MrStock\Business\ServiceSdk\Hook\BeforeUpdateDelete'],
    
    'before_delete'      => ['MrStock\Business\ServiceSdk\Hook\BeforeUpdateDelete'],
    
    'login_success'      => ['App\Hook\UpdateLoginIp','App\Hook\RectifyPassWord']
];

/*作用于v=xx xx对应的行为 extends application （相同键既重写）*/
$config['hooks']['app'] = [
];

$config['hooks']['manager'] = [
    
];

$config['hooks']['cliapi'] = [
    
    
];

$config['hooks']['cli'] = [
    
    'before_select'      => [],
    
    'before_insert'      => [],
    
    'before_update'      => [],
    
    'before_delete'      => [],
    
    'login_success'      => []
];
return $config;