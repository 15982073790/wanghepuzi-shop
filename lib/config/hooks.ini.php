<?php
$config = [];

//默认行为
$config['hooks']['application'] = [
    
    'debug_record' => ['MrStock\Business\ServiceSdk\Hook\DebugRecord'],
    
];


return $config;