<?php

$config['db']['default'] = [
        'read' => [
            'host' => getenv('db.host'),
            'port' => getenv('db.port')
        ],
        'write' => [
            'host' => getenv('db.host'),
            'port' => getenv('db.port')
        ],
        'driver' => 'mysqli',
        'name' => getenv('db.name'),
        'user' => getenv('db.user'),
        'pwd' => getenv('db.pwd'),
        'charset' => 'utf-8',
        'tablepre' => getenv('db.tablepre')
    ];

return $config;