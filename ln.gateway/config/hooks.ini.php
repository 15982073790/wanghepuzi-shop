<?php
$config = [];
$config['hooks']['application'] = [
    'before_select'      => ['MrStock\Business\ServiceSdk\Hook\Cxt\BeforeSelect'],
];
return $config;