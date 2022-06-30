<?php
$config = [];

//默认行为
$config['hooks']['v2']['v2'] = [
    
    'debug_record' => ['MrStock\Business\ServiceSdk\Hook\DebugRecord'],
    
];


return $config;