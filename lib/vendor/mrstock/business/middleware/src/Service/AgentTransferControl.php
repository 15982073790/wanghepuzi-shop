<?php
namespace MrStock\Business\Middleware\Service;

use MrStock\System\MJC\Http\Request;
use MrStock\System\Helper\Output;
use MrStock\Business\ServiceSdk\JsonRpc\RpcClientFactory;
use MrStock\System\Helper\Config;

/**
 * 代理应用端使用的中间件-帮客户端中转服务
 */
class AgentTransferControl
{

    private $request;

    public function handle(Request $request, \Closure $next)
    {
        $this->request = $request;
        if ($this->request->site){
            $mothd=Config::get("rpctype");
            $result=RpcClientFactory::$mothd($this->request->param);

            return  Output::response($result["data"],$result["code"]);
        }
        return $next($request);
    }


}