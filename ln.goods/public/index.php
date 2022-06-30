<?php

// 是否开启调试模式
define("APP_DEBUG", true);

// 是否命令行
define('IS_CLI', 0);

// 是否接口模式
define('IS_API', 1);

require_once 'app.php';

MrStock\System\MJC\Facade\App::run()->send();
?>