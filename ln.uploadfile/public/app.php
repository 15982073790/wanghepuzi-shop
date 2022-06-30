<?php
// 应用入口目录
define('PUBLIC_PATH', __DIR__ . '/../');
define('AVATER_PATH', dirname(__DIR__).'/data/avater/');

// composer 组件目录
define('VENDOR_DIR', '/data/lib/vendor');

require_once VENDOR_DIR . '/../public/base.php';
?>