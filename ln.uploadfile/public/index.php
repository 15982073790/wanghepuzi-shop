<?php
// 是否开启调试模式
define("APP_DEBUG", true);
//if(!isset($_REQUEST['appcode'])){
//    $_REQUEST['appcode'] = '5c3d73a0f1f18c30cqb5zkov';
//}if(!isset($_REQUEST['appkey'])){
//    $_REQUEST['appkey'] = '5b6ab2f42cccefjv3gwy2vn1';
//}
// 是否命令行
define('IS_CLI', 0);

// 是否接口模式
define('IS_API', 1);

require_once 'app.php';

MrStock\System\MJC\Facade\App::run()->send();
?>