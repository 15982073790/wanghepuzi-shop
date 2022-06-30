<?php

namespace MrStock\Business\Middleware\Service;

use MrStock\System\MJC\Http\Request;
use MrStock\System\Helper\Output;
use MrStock\Business\ServiceSdk\JsonRpc\RpcClientFactory;
use MrStock\System\Helper\Config;
use MrStock\System\Helper\RpcRequest;
/**
 * 服务鉴权中间件
 */
class ServiceAuthControl
{

    private $request;

    /**
     * @param Request $request
     * @param \Closure $next
     * @return mixed|\MrStock\System\Helper\unknown
     */
    public function handle(Request $request, \Closure $next)
    {
        $this->request = $request;
        try {
            $this->checkAuthenticate();
        } catch (\Exception $e) {
            return Output::response($e->getMessage(), $e->getCode());
        }
        return $next($this->request);
    }

    private function checkAuthenticate()
    {
        $site = $this->request->site;
        $v = $this->request->v;
        $c = $this->request->c;
        $a = $this->request->a;
        if (empty($site) || empty($c) || empty($a)) {
            throw new \Exception('接口不正确', '-1003');
        }
        if(strtolower($v)=="inneruse"){
            if ($this->request->param["inneruse_secretkey"]!=Config::get("inneruse_secretkey")) {
                throw new \Exception('inneruse_secretkey不正确', '-1002');
            }
        }
        if (strtolower($v) == "manager") {
            $data['check_c'] = $c;
            $data['check_a'] = $a;
            $data['check_v'] = $v;
            $data['check_site'] = $site;
            $data['admin_id'] = $this->request->admin_id;
            $data['key_token'] = $this->request->key_token;
            $res = RpcRequest::syncRequest('gateway-auth-adminisauth',$data);
            if ($res['code'] != 1){
                throw new \Exception($res["message"], $res["code"]);
            }
        } else if (substr(strtolower($v), 0, 4) == "user") {
//            $member_id = $this->request->param["member_id"];
//            $key = $this->request->param["key"];
//            if (empty($member_id) || empty($key)) {
//                throw new \Exception('用户账号登录参数错误', '-1007');
//            }
//            $url = "http://login.api.guxiansheng.cn/index.php?c=user_cxt&a=token_auth";
//            $res = HttpRequest::query($url,['member_id'=>$member_id,'key'=>$key]);
//            if ($res["code"] != 1) {
//                throw new \Exception($res["message"], $res["code"]);
//            }
        }
    }
}
