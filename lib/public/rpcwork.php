<?php
use Workerman\Worker;
use MrStock\Business\Middleware\Service\RpcStartDeal;
// 开启的端口
ini_set('display_errors', 'on');
//检查扩展
if(!extension_loaded('pcntl'))
{
    exit("Please install pcntl extension. See http://doc3.workerman.net/install/install.html\n");
}

if(!extension_loaded('posix'))
{
    exit("Please install posix extension. See http://doc3.workerman.net/install/install.html\n");
}
$vendor_worker_ipport=getenv('worker_ipport');
$vendor_worker_count=getenv('worker_count');
$vendor_worker_name=getenv('worker_name');

if(empty($vendor_worker_ipport)||empty($vendor_worker_count)||empty($vendor_worker_name)){
    exit("vendor .env file err");
}

$worker = new Worker("\MrStock\Business\Middleware\Service\\".$vendor_worker_ipport);
// 启动多少服务进程
$worker->count = $vendor_worker_count;
$worker->name = $vendor_worker_name;

$worker->reloadable = false;
Worker::$pidFile = '/tmp/workerman_'.$worker->name.'.pid';
$worker->onMessage = function($connection, $data)
{

    $statistic_address = "";
    // 判断数据是否正确
    if(empty($data['class']) || empty($data['method']) || !isset($data['param_array']))
    {
        // 发送数据给客户端，请求包错误
       return $connection->send(array('code'=>400, 'msg'=>'bad request', 'data'=>null));
    }
    $data['param_array'][0]["RPC_PATH"]='/data/rpcwww/'.$data['param_array'][0]['site'].'.service/public/rpc.php';

    // 获得要调用的类、方法、及参数
    $class = $data['class'];
    $method = $data['method'];
    $param_array = $data['param_array'];

    $param_array=$param_array[0];
    $success = false;

    try
    {
        $ret=RpcStartDeal::deal( $param_array);
        // 发送数据给客户端，调用成功，data下标对应的元素即为调用结果
        return $connection->send($ret);
    }
    // 有异常
    catch(Exception $e)
    {
        // 发送数据给客户端，发生异常，调用失败
        $code = $e->getCode() ? $e->getCode() : 500;

        return $connection->send($e);
    }

};
 Worker::runAll();
