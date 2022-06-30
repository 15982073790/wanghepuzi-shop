<?php
/**
 * run with command 
 * php start.php start
 */
ini_set('display_errors', 'on');
use Workerman\Worker;

//检查扩展
if(!extension_loaded('pcntl'))
{
    exit("Please install pcntl extension. See http://doc3.workerman.net/install/install.html\n");
}

if(!extension_loaded('posix'))
{
    exit("Please install posix extension. See http://doc3.workerman.net/install/install.html\n");
}
$site="";
if(!empty($argv)){
	foreach ($argv as $key => $value) {

		if(isset($value)&&$value=="-s"){
			$site=$argv[$key+1];
			unset($argv[$key]);
			unset($argv[$key+1]);
		}
	}
}
if(empty($site)){
	echo "no -s site";exit;
}
// 标记是全局启动
define('GLOBAL_START', 1);

require_once '/data/lib/vendor/autoload.php';
require_once __DIR__.'/Applications/BaseDeal.php';
$config=require_once __DIR__.'/Applications/Config.php';

if(count($config)>0){
	$value=$config[$site];
	$_GET['START_CONFIG']=json_encode($value);
	$_GET['selfpid_flag']=$site;
	require __DIR__.'/Applications/worker/start.php';
}


// require __DIR__.'/Applications/Statistics/start.php';

// 运行所有服务
Worker::runAll();
