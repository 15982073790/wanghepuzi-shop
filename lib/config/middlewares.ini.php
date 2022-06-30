<?php
$config = [];

$config['middlewares']['application'] = 
    [
        'MrStock\System\MJC\Filter',
//        'MrStock\Business\Middleware\Service\ServiceSDKRegister',
        'MrStock\Business\Middleware\Service\ServiceAuthControl'
    ];
return $config;