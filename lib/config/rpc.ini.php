<?php
$config = array();
$config['rpc']['gateway'] = ['tcp://127.0.0.1:2020'];
$config['rpc']['user'] = ['tcp://127.0.0.1:2021'];
$config['rpc']['goods'] = ['tcp://127.0.0.1:2022'];

$config['rpctype']      = 'sync';
$config['inneruse_secretkey'] = 'linetest1234.';
return $config;