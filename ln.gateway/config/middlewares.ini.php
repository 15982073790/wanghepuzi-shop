<?php
$config = [];
\MrStock\System\Helper\Config::unsetKey('middlewares');
$config['middlewares']['application'] =
    [
        'MrStock\System\MJC\Filter',
//        'Common\Middleware\AuthMiddleware',
    ];

return $config;