<?php

namespace CxtCloud\Client;

use MrStock\System\Helper\Config;
use MrStock\System\Helper\HttpRequest;

class CxtClient
{
    public static function __callstatic($method, $param) {
        try{
            $res = self::httpRequest($method, $param[0]);
            return $res;
        }catch(\Exception $e){
            return ['code'=>$e->getCode(), 'message'=>$e->getMessage()];
        }
    }

    public static function httpRequest($method,$data)
    {

        try {
            $arr = explode('_',$method,3);
            $data['site'] = $arr[0];
            $data['c'] = $arr[1];
            $data['a'] = $arr[2];
            $data['v'] = 'inneruse';
            $data['inneruse_secretkey'] = Config::get("rpc_inneruse_secretkey");
            if (empty($data['inneruse_secretkey'])) {
                $data['inneruse_secretkey'] = c("rpc_inneruse_secretkey");
            }
            $url = "http://".$data['site'].".api.caixuetang.cn/index.php";
            return HttpRequest::query($url,$data,1);
        } catch (\Exception $e) {
            throw  new \Exception($e->getMessage(), $e->getCode());
        }
    }
}