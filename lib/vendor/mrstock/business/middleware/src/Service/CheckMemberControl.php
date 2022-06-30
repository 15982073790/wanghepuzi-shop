<?php
namespace MrStock\Business\Middleware\Service;

use MrStock\System\MJC\Http\Request;
use MrStock\System\Helper\Output;
use MrStock\Business\ServiceSdk\JsonRpc\RpcClientFactory;
use MrStock\System\Helper\Config;
/**
 * 应用端使用的中间件-鉴权ServiceToken
 */
class CheckMemberControl
{

    private $request;

    public function handle(Request $request, \Closure $next)
    {

        $this->request = $request;

        if(!$this->request->authMemberId){

            return Output::response("没有登录", "-9999");
        }        
        return $next($this->request);
    }


}