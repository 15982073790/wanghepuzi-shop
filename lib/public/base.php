<?php
define('VERSION',"1.0.0");

//应用目录
define('APP_PATH', PUBLIC_PATH. '/application/');

//应用配置文件
define('CONFIG_DIR',PUBLIC_PATH. '/config/');

//默认控制器目录
define('CONTROL_DIR',APP_PATH);

//公用配置文件目录
define('VENDOR_CONFIG_DIR',VENDOR_DIR. '/../config/');

require_once VENDOR_DIR."/autoload.php";

if(empty($_REQUEST["rpc_msg_id"])){
    $rpc_msg_id=md5(json_encode($_REQUEST,JSON_UNESCAPED_UNICODE));
    $_REQUEST["rpc_msg_id"]=$rpc_msg_id;
}
$msectime=MrStock\System\Helper\Tool::msectime();
$rpc_msg_time=MrStock\System\Helper\Tool::get_microtime_format($msectime*0.001);
$_REQUEST["rpc_msg_time"]=$rpc_msg_time;

define('ENV_DIR',PUBLIC_PATH. '/public/');
define('VENDOR_ENV_DIR',VENDOR_DIR. '/../public/');

$dotenv = Dotenv\Dotenv::create([ENV_DIR,VENDOR_ENV_DIR]);
$dotenv->load();

            
?>