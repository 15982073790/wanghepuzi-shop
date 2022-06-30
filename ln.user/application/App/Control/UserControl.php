<?php

namespace App\Control;


use MrStock\System\MJC\Control;
use MrStock\System\MJC\Http\Request;
use MrStock\System\Helper\Arr;
use Common\Model\User;
use Common\Model\Area;
use Common\Logic\UserLogic;
use MrStock\System\Helper\Config;
use Common\Libraries\AppTools;
use MrStock\System\Helper\HttpRequest;

/**
 * @OpDescription(whatFor="用户",codeMonkey="")
 */
class UserControl extends Control
{
    public $middleware = [
        'control' => [],
        'adduserinfoOp'=>['\Common\Middleware\UserAdduserinfo']
    ];
    public function __construct()
    {

        $this->xcxappid = Config::get('xcx_appid');
        $this->xcxsecret = Config::get('xcx_secret');
    }
    /**
     * @OpDescription(whatFor="获取小程序openid",codeMonkey="")
     */
    public function useropenidOp(Request $request){
        $code = $request['code'];
        $access_token_response = HttpRequest::query('https://api.weixin.qq.com/sns/jscode2session',['appid'=>$this->xcxappid,'secret'=>$this->xcxsecret,'js_code'=>$code,'grant_type'=>'authorization_code']);
        if($access_token_response['errcode'] != 0){
            return $this->json($access_token_response['errmsg'],$access_token_response['errcode']);
        }
        return $this->json($access_token_response,1);
    }
    /**
     * @OpDescription(whatFor="用户个人中心",codeMonkey="")
     */
    public function usercenterOp(Request $request,User $new_user){
        $wx_openid                 = $request['wx_openid'];
        $user_find                 = $new_user->findByCondition(['wx_openid' => $wx_openid]);
        $user_find['coupon_money'] = sprintf("%.2f", $user_find['coupon_money'] / 100);
        $user_find['is_expire']    = $user_find['expire_time']>time() ? 1 : 2;//1未过期,2已过期
        unset(
            $user_find['itime'],
            $user_find['utime'],
            $user_find['dtime'],
            $user_find['datastatus']
        );
        $res = $user_find;
        return $this->json($res);
    }
    /**
     * @OpDescription(whatFor="用户授权",codeMonkey="")
     */
    public function adduserinfoOp(Request $request, User $new_user)
    {
        $wx_openid = $request['wx_openid'];
        $wx_name = $request['wx_name'];
        $wx_avatar = $request['wx_avatar'];
        $user_find = $new_user->findByCondition(['wx_openid'=>$wx_openid]);
        if(empty($user_find)){
            $new_user->insertData(['wx_openid'=>$wx_openid,'wx_name'=>$wx_name,'wx_avatar'=>$wx_avatar]);
        }
        return $this->json('获取成功');
    }
    /**
     * @OpDescription(whatFor="是否绑定",codeMonkey="")
     */
    public function isbandOp(Request $request, User $new_user){
        $wx_openid = $request['wx_openid'];
        $user_find = $new_user->findByCondition(['wx_openid'=>$wx_openid]);
        if(empty($user_find)){
            $res['is_bind'] = 2;
        }else{
            $res['is_bind'] = 1;
        }
        return $this->json($res);
    }
}
