<?php

namespace MrStock\Business\ServiceSdk\RpcSdk;

use MrStock\Business\ServiceSdk\JsonRpc\RpcClientFactory;
use MrStock\System\Helper\Config;
use MrStock\System\MJC\Container;

class RpcCommon
{
    //调用demo
//$rs=\MrStock\Business\ServiceSdk\RpcSdk\RpcCommon::gateway_Admin_selectbyadminids(['admin_ids'=>'1,2,3,6']);
//$rs=\MrStock\Business\ServiceSdk\RpcSdk\RpcCommon::gateway_Admin_getadminidbyusername(["username"=>"3Y2FLi","ss"=>11]);
    public static function __callstatic($method, $args) {
        try{
            $args=static::dealargs($method, $args);
            $args[0]=empty($args[0])?[]:$args[0];
            $res=static::run(array_merge($args[0],static::before_method($method)));            
            return $res;
        }catch(\Exception $e){
            return ['code'=>$e->getCode(), 'message'=>$e->getMessage()];
        }

        
    }
    
    public static function before_method($method)
    {
        $requests = Container::get("request");
       
        $method_arr=explode('_', $method);
        if(count($method_arr)<3){
             throw new \Exception("调用的内部接口有误",-1);
        }elseif (count($method_arr) > 3) {
            $method_arr[2] = implode('_', array_slice($method_arr, 2));
        } 
        
        if(empty(Config::get("rpc_inneruse_secretkey"))){
             throw new \Exception("rpc_inneruse_secretkey没有配置",-1);
        }
        
        $data['site'] = $method_arr[0];
        $data['c'] = $method_arr[1];
        $data['a'] = $method_arr[2];
        $data['v'] = "inneruse";
        $data['appcode'] = $requests->appcode;
        $data['inneruse_secretkey'] = Config::get("rpc_inneruse_secretkey");
        return $data;
    }

    public static function run($data)
    {

        $res = RpcClientFactory::sync($data);
        return $res;
    }
    public static function dealargs($method, $args)
    {
        if($method=="message_Access_inqueue"){
            $args[0]["businessdata"]=\MrStock\System\Helper\Tool::arrToStr($args[0]["businessdata"]);
        }
        
        return $args;
    }
    

}
