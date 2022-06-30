<?php
$config = [];

//作用于所有版本
$config['middlewares']['application'] = [
    'MrStock\System\MJC\Filter',
    'App\Middleware\MobileEncrypt'
];

/*作用于v=xx  xx对应的版本 与application 并集*/
$config['middlewares']['app'] = [
    'MrStock\Business\Middleware\Service\AuthenticateRpcControl',
];

$config['middlewares']['manager'] = [
    'MrStock\Business\Middleware\Service\AuthenticateRpcControl',
];

return $config;