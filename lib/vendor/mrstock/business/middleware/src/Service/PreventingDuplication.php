<?php

namespace MrStock\Business\Middleware\Service;

use MrStock\System\MJC\Http\Request;
use MrStock\System\Helper\Output;
use MrStock\System\Redis\RedisHelper;
/**
 * 数据修改防止重复
 */
class PreventingDuplication
{
    const time_delete = 10;//单位s
    /**
     * @param Request $request
     * @param \Closure $next
     * @return mixed|\MrStock\System\Helper\unknown
     */
    public function handle(Request $request, \Closure $next)
    {
        try {
            $result=$this->isDuplication($request->param);
            if($result){
                return Output::response("本次提交重复，请".self::time_delete."秒后重试", - 1);
            }
        } catch (\Exception $e) {
            return Output::response($e->getMessage(), $e->getCode());
        }
      
        return $next($request);
    }
    public function isDuplication($requestparam)
    {

        $redis = new RedisHelper('appcluster', 0);
        
        if(isset($requestparam["rpc_msg_time"])) unset($requestparam["rpc_msg_time"]);
        if(isset($requestparam["rpc_msg_id"])) unset($requestparam["rpc_msg_id"]);
        if(isset($requestparam["callback"])) unset($requestparam["callback"]);

        $redis_key=md5(json_encode($requestparam,JSON_UNESCAPED_UNICODE));
       
        if(!empty($redis->get($redis_key))) return 1;
    
        $rs=$redis->set($redis_key, 'PreventingDuplication');
        
        $rs=$redis->expire($redis_key, self::time_delete);
        
        return 0;
    }
    
}
