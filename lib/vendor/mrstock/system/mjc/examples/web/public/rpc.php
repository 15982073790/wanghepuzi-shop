<?php
//是否开启调试模式
define("APP_DEBUG", true);

//是否命令行
define('IS_CLI', 1);

//是否接口模式
define('IS_API', 0);

//应用入口目录
define('PUBLIC_PATH', __DIR__ . '/../');

//composer 组件目录
define('VENDOR_DIR','D:/Project/vendor/trunk/vendor');

require_once VENDOR_DIR. '/../public/app.php';
?>