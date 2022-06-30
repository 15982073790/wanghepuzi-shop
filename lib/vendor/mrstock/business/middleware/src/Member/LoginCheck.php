<?php
namespace MrStock\Business\Middleware\Member;

use MrStock\System\MJC\Facade\Log;
use MrStock\System\MJC\Http\Request;
use MrStock\System\Helper\Output;
use MrStock\System\Orm\Model;
use MrStock\Business\Middleware\Member\Model\AuthUserTokenModel;

class LoginCheck
{

    public function handle(Request $request, \Closure $next)
    {
        $member_id = intval($request->param['member_id']);
        
        $limit_type = $request->param['limit_type'];
        $key_sxjg = $request->param['key_sxjg'];
        $key = $request->param['key'];
        
        /**
         * 三方首席荐股，验证key
         */
        if (! empty($limit_type) && ! empty($key_sxjg) && ! empty($member_id) && $member_id == 1) {
            $model_seller_limit_extend = new Model('seller_limit_extend');
            $key_sxjg_table = $model_seller_limit_extend->field('key_sxjg')
                ->where("$limit_type=2 and $member_id=1")
                ->find();
            $tokey_sxjg = $key_sxjg_table['key_sxjg'] ? trim($key_sxjg_table['key_sxjg']) : '';
            if ($key_sxjg == $tokey_sxjg) {
                $user_token_info['limit_type'] = $limit_type;
                $user_token_info['member_id'] = $member_id;
            } else {
                return Output::response('key 过期'. __LINE__,- 2);
            }
        } else {
            if ($member_id > 0) {
                
                $model_auth_user_token = new AuthUserTokenModel();
                
                $user_token_info = $model_auth_user_token->getUserTokenInfoByID($member_id, trim($request->param['route_mark']), $key);
            }
            
            if (empty($user_token_info)) {
                
                return Output::response('请登录'. __LINE__,- 1);
            }
            
            if ($user_token_info["token"] != $key) {
                Log::record('login_error===>登录失败id：' . $member_id . ',查询出来的key：' . $user_token_info["token"] . ',传递的key：' . $key . '|c=' . $request->param['c'] . '|a=' . $request->param['a'] . '|v=' . $request->param['v']);
                
                return Output::response('key 过期'. __LINE__,- 2);
            }
        }
        
        $request->user_token = $user_token_info;
        
        return $next($request);
    }
}