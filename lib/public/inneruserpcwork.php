<?php

use Workerman\Worker;
use MrStock\Business\Middleware\Service\RpcStartDeal;
use MrStock\System\Orm\Model;
use MrStock\System\Helper\Config;


    function check(){
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
    }
    function getServerIp(){ 
        $mingling=getenv("ip_cmd");
        return exec($mingling);      
    } 
    function create(){
      

        Config::register();
        check();
        $start_port=getenv("inneruse_start_port");
        $worker_count=getenv("inneruse_worker_count");
        $service_name=getenv("worker_name");
        $model = new Model('', 'gateway');
        $allinneruseapi = $model->table('api')->where(["v"=>"inneruse","site"=>$service_name])->select();
        if(empty($allinneruseapi)){
         exit("no inneruseapi");
        }
        $deal_ip_port=[];
        $ip=getServerIp();
        $url=VENDOR_DIR . '/../config/inneruseapi_'.$service_name.'.ini.php';
        if(file_exists($url)){
            $inneruseapi_arr=require $url; 
        }else{
            $inneruseapi_arr=[];
        }
        

        foreach ($allinneruseapi as $key => $value) {
            $port=$start_port+$key;
            $vendor_worker_ipport="JsonNL://0.0.0.0:".$port;
            $vendor_worker_count=$worker_count;
            $vendor_worker_name=$value['site'].'-'.$value['c'].'-'.$value['a'].'-'.$value['v'].'-'.$value['api_version'];

            $arr=$inneruseapi_arr[$vendor_worker_name];
            $arr=empty($arr)?[]:$arr;
            $thisItem=$ip.":".$port;

            if($_SERVER['argv'][1]=="start"){
                if(!empty($arr)){
                    foreach ($arr as $key => $value) {
                        $tmp_arr=explode(':', $value);
                        if($tmp_arr[0]==$ip){
                                unset($arr[$key]);
                        }
                    
                    }
                }
                

               $arr[]=$thisItem;
               $deal_ip_port[$vendor_worker_name]=implode(',', $arr);
            }

            if($_SERVER['argv'][1]=="stop"&&!empty($arr)){

               foreach ($arr as $key => $value) {
                    if($value==$thisItem){
                        unset($arr[$key]);
                    }
               }
               $deal_ip_port[$vendor_worker_name]=implode(',', $arr);
            }
            

            creatework($vendor_worker_ipport,$vendor_worker_count,$vendor_worker_name);
        }

        
        
        if(in_array($_SERVER['argv'][1],['stop','start'])){
            writeini($url,$deal_ip_port);
        }
        Worker::runAll();
        
    

    }
    function writeini($url,$data){
        $str="<?php\n";
        $str.="\$config = array();\n";
        foreach ($data as $key => $value) {
            # code...
            $tmp_arr=explode(',', $value);
            if(count($tmp_arr)>1){
                $value=implode("','", $tmp_arr);
            }
            $valuestr="'".$value."'";
           
            $str.="\$config['".$key."'] = [$valuestr];\n";
        }
        $str.="return \$config;";
        
        file_put_contents($url, $str);
    }
    function creatework($vendor_worker_ipport,$vendor_worker_count,$vendor_worker_name){
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
}
create();