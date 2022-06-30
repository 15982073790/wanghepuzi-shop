<?php
$config = array();

$config['gateway']['ipPort']='JsonNL://0.0.0.0:2020';
$config['gateway']['workerCount']=5;
$config['gateway']['workerName']='gateway';


$config['user']['ipPort']='JsonNL://0.0.0.0:2021';
$config['user']['workerCount']=5;
$config['user']['workerName']='user';

$config['goods']['ipPort']='JsonNL://0.0.0.0:2022';
$config['goods']['workerCount']=5;
$config['goods']['workerName']='goods';




 
return $config;
?>