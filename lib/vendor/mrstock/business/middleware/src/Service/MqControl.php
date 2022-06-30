<?php
namespace MrStock\Business\Middleware\Service;
use MrStock\System\Helper\Output;
use MrStock\System\MJC\Http\Request;
use MrStock\System\Helper\Config;
use MrStock\Business\ServiceSdk\PhpAmqpLibSdk\PhpAmqpLibFactory;
/**
 * 代理应用端使用的中间件-帮客户端中转服务
 */
class MqControl
{

    private $request;

    public function handle(Request $request, \Closure $next)
    {
        $mqstr=empty($request["serviceversion"])?'hooks.mq':'hooks.'.$request["serviceversion"].'.mq';
        if($request["c"]=="inihook"){
            $mq=Config::get($mqstr);
            if(empty($mq)){
                $mq=[];
            }
            return Output::response($mq, 1);
        }

        if($request["c"]=="send"){
            //for ($i=0; $i <10 ; $i++) { 
            
                \MrStock\Business\ServiceSdk\PhpAmqpLibSdk\PhpAmqpLibFactory::say("event1",$msg=["num"=>1,"id"=>"1","pic"=>"jiulin"]);
               
            //     sleep(1);
            //     # code...
            // }
            exit;
        }
        


        $target_site=$request["c"];
        $target_event=$request["a"];
        $hook_name=$target_site.'_'.$target_event;
        $mq=Config::get($mqstr);
        $mq_key=array_keys($mq);
        if(empty($mq_key)||!in_array($hook_name, $mq_key)){
            return Output::response("no hook ini", - 1);
        }

        

        $hook_class=$mq[$hook_name];
        PhpAmqpLibFactory::listen($target_site,$target_event,$hook_class);
        
        return true;
    }


}
