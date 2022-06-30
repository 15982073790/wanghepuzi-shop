<?php
//是否开启调试模式
define("APP_DEBUG", true);

//应用目录
define('APP_PATH', __DIR__);

//composer 组件目录
define('VENDOR_DIR','D:/Project/vendor/trunk/vendor');

require_once VENDOR_DIR."/autoload.php";


Use MrStock\System\Orm\Model;
Use MrStock\System\Helper\Config;

define("VENDOR_CONFIG_DIR","null");
define("CONFIG_DIR",__DIR__."/config/");
Config::register();
