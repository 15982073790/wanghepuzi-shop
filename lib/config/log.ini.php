<?php

$config = array();

$config['log']['dir'] 					= "/data/logs";
$config['log']['indxfields'] 			=  ["member_id","client_id","c","a","v","admin_id","employee_id"];
$config['log']['filterfields'] 			=  ["avatar_content"];
$config['log']['level'] 					=  ['ERR',
												'ACCESS',
												'ROUTEERR',
												'ROUTENONE',
												'DBERR',
												'DBSLOW',
												'DBROLLBACK',
												'REDISERR',
												'CURLERR'
												];
return $config;