<?php
namespace App\Control;

use MrStock\System\MJC\Control;
use MrStock\System\Helper\Log;
use App\Model\UserTokenModel;

class ServiceControl extends Control{


    /**
     * 用户登录生成token
     *
     * @param mixed $member_id This is a description
     * @param mixed $member_name This is a description
     * @param mixed $client This is a description
     * @return mixed This is the return value description
     *
     */
    public function _get_token($member_id, $member_name) { 
        $model_user_token = new UserTokenModel();

        //单点登录 重新登录后以前的令牌失效
        $condition = array();
        $condition['member_id'] = $member_id;
        $route_mark=trim($_REQUEST['route_mark']);
        if($route_mark!=''){
            $condition['route_mark'] = $_REQUEST['route_mark'];
        }else{
            $condition['route_mark'] = '';
        }
        
        $model_user_token->delUserToken($condition);

        //生成新的token
        $user_token_info = array();
        $token = md5($member_name . strval(time()) . strval(rand(0,999999)));
        $user_token_info['member_id']		= $member_id;
        $user_token_info['member_name']	= $member_name;
        $user_token_info['token']			= $token;
        $user_token_info['login_time']	= time();
        $user_token_info['client_type']	= empty($this->useragent) ? "cxt_houtai" : $this->useragent;
        $user_token_info['route_mark']	= empty($condition['route_mark'])?'app':trim($condition['route_mark']);

        if($user_token_info['member_name']==''){
            Log::record('写入user_token表时，member_name为空：'.$user_token_info['member_id']);
        }
        $result = $model_user_token->addUserToken($user_token_info);

        if($result) {
            return $token;
        }

        return null;
    }

    /**获取登录用户的令牌信息
     * @param $member_id
     * @param $member_name
     * @return null
     * author xumin
     */
    public function _get_token_cxt($member_id, $member_name, $client_type='h5') {
        $model_user_token = Model('user_token_cxt');
        $condition = array();
        $condition['member_id'] = $member_id;
        $condition['client_type'] = $client_type;
        //查询令牌信息
        $result = $model_user_token->getUserToken($condition);
        
        if($result) {
            //存在返回
            return $result['token'];
        }else{
            //不存在，重新生成
            $user_token_info = array();
            $token = md5($member_name . strval(time()) . strval(rand(0,999999)));
            $user_token_info['member_id']		= $member_id;
            $user_token_info['member_name']	= $member_name;
            $user_token_info['token']			= $token;
            $user_token_info['login_time']	= time();
            $user_token_info['client_type']	= $client_type;
            $result = $model_user_token->addUserToken($user_token_info);
            if($result) {
                return $token;
            }else{
                return null;
            }
        }
    }


    /**cxt 登录获取登录用户的令牌信息
     * @param $member_id
     * @param $member_name
     * @return null
     * author xumin
     */
    public function get_token_by_cxt_login($member_id, $member_name, $client_type='h5') {
        $model_user_token = Model('user_token_cxt');
        $condition = array();
        $condition['member_id'] = $member_id;
        $condition['client_type'] = $client_type;
        //查询令牌信息
        // $result = $model_user_token->getUserToken($condition);
        //删除以前的令牌
        $model_user_token->delUserToken($condition);
        
        //不存在，重新生成
        $user_token_info = array();
        $token = md5($member_name . strval(time()) . strval(rand(0,999999)));
        $user_token_info['member_id']		= $member_id;
        $user_token_info['member_name']	= $member_name;
        $user_token_info['token']			= $token;
        $user_token_info['login_time']	= time();
        $user_token_info['client_type']	= $client_type;
        $result = $model_user_token->addUserToken($user_token_info);
        if($result) {
            return $token;
        }else{
            return null;
        }
    }
}