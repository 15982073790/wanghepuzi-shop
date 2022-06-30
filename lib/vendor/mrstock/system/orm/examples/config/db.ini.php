<?php
$config['db'] = [
    'default' => [
        'read' => [
            'host' => '192.168.10.230',
            'port' => '3306'
        ],
        'write' => [
            'host' => '192.168.10.230',
            'port' => '3306'
        ],
        'driver' => 'mysqli',
        'name' => 'stocksir',
        'user' => 'stocksir',
        'pwd' => 'stocksir1704!',
        'charset' => 'utf-8',
        'tablepre' => 'stock_'
    ]
];

return $config;