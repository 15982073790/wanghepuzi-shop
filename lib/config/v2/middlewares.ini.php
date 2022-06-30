<?php
$config = [];

$config['middlewares']['v2']['v2'] =
    [
        'MrStock\System\MJC\Filter',
        'MrStock\Business\Middleware\Service\ServiceSDKRegister',
        'MrStock\Business\Middleware\Service\ServiceAuthControl'
    ];
return $config;