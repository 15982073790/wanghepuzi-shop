<?php
namespace MrStock\Business\Middleware\Service;

use MrStock\System\MJC\Http\Request;
use MrStock\System\Helper\Output;
use MrStock\Business\ServiceSdk\JsonRpc\RpcClientFactory;

/**
 * restfulapi验证基类
 */
class AuthenticateRpcControl
{

    private $request;

    public function handle(Request $request, \Closure $next)
    {
        $this->request = $request;
        try {
            $this->checkAuthenticate();
            if (! is_array($this->request->param->appcode)) {
                
                $this->appcodeApi();
            }
        } catch (\Exception $e) {
            return Output::response($e->getMessage(), $e->getCode());
        }
        
        return $next($this->request);
    }

    /**
     * app_key与app_code验证
     */
    private function checkAuthenticate()
    {
        if (! empty($this->request->server["HTTP_SERVICESTOKEN"])) {
            $this->request->param["servicestoken"] = $this->request->server["HTTP_SERVICESTOKEN"];
            $this->request->param["servicesuid"] = $this->request->server["HTTP_SERVICESUID"];
        } else {
            $request_content = file_get_contents('php://input');
            $request_other = json_decode($request_content, true);
            if (! is_array($request_other)) {
                parse_str($request_content, $request_other);
            }
            if (is_array($request_other) && isset($request_other["SERVICESTOKEN"])) {
                $this->request->param["servicestoken"] = $request_other["SERVICESTOKEN"];
            }
        }
        
        if (empty($this->request->param["servicestoken"])) {
            throw new \Exception('服务验证的参数必传', '-1002');
        }
        
        // 配置服务端列表
        $data['site'] = "base";
        $data['refersite'] = $this->request->site;
        $data['c'] = "server";
        $data['a'] = "checkServersToken";
        $data['servicestoken'] = $this->request->param["servicestoken"];
        $res = RpcClientFactory::sync($data);

        $this->request->appcode = $res['data']['app_code'][0];
        $this->request->appcodelist = implode(',', $res['data']['app_code']);
        $this->request->param_info = $res['data']['param_info'];
        $this->request->authMemberId=$res['data']['member_id'];
        if($res['data']['member_id']){
            $this->request->member_id=$res['data']['member_id'];
        }
    }

    /**
     * app_key与app_code验证
     */
    private function appcodeApi()
    {
        $url = $this->getUrl($this->request);
        // 用rpc同步获取
        
        // 配置服务端列表
        $data['site'] = "base";
        $data['refersite'] = $this->request->site;
        $data['c'] = "Appcodeapi";
        $data['a'] = "checkappcodeapi";
        $data['app_code'] = $this->request->appcode;
        $data['url'] = $url;
        $data['method'] = "get";
        
        RpcClientFactory::sync($data);
    }

    public function getUrl()
    {
        $sys_protocal = isset($this->request->server['SERVER_PORT']) && $this->request->server['SERVER_PORT'] == '443' ? 'https://' : 'http://';
        $php_self = $this->request->server['PHP_SELF'] ? $this->request->server['PHP_SELF'] : $this->request->server['SCRIPT_NAME'];
        $path_info = isset($this->request->server['PATH_INFO']) ? $this->request->server['PATH_INFO'] : '';
        $relate_url = isset($this->request->server['REQUEST_URI']) ? $this->request->server['REQUEST_URI'] : $php_self . (isset($this->request->server['QUERY_STRING']) ? '?' . $this->request->server['QUERY_STRING'] : $path_info);
        return $sys_protocal . (isset($this->request->server['HTTP_HOST']) ? $this->request->server['HTTP_HOST'] : '') . $relate_url;
    }
}