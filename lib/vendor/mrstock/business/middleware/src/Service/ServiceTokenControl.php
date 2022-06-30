<?php
namespace MrStock\Business\Middleware\Service;

use MrStock\System\MJC\Http\Request;
use MrStock\System\Helper\Output;
use MrStock\Business\ServiceSdk\JsonRpc\RpcClientFactory;
use MrStock\System\Helper\Config;
/**
 * 应用端使用的中间件-鉴权ServiceToken
 */
class ServiceTokenControl
{

    private $request;

    public function handle(Request $request, \Closure $next)
    {

        $this->request = $request;
        try {
            $this->get();
        } catch (\Exception $e) {
            return Output::response($e->getMessage(), $e->getCode());
        }
        
        return $next($this->request);
    }

    /**
     * app_key与app_code验证
     */
    private function get()
    {
        $data['site'] = "base";
        $data['c'] = "Token";
        $data['a'] = "get";
        $data['appcode'] = Config::get("appcode");

        $data['member_id'] = $this->request->member_id;
        $data['key'] = $this->request->key;
        $res = RpcClientFactory::sync($data);
        
        $this->request->servicestoken = $res["data"]['servicestoken'];
    }
}