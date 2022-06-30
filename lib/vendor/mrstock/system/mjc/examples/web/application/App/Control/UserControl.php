<?php
namespace App\Control;

use MrStock\System\MJC\Facade\Log;
use MrStock\System\MJC\Validate;
use MrStock\System\MJC\Control;
use MrStock\System\Orm\Model;
use MrStock\Business\Base\Logic\ReLogic;
use MrStock\Business\Base\Logic\ChangeTelLogic;

use App\Model\MemberModel;
use MrStock\System\MJC\Http\Request;

class UserControl extends Control
{

    /**
     * 用户登录
     *
     * @return mixed This is the return value description
     *
     */
    public function loginOp(Request $request)
    {

        $request ["username"] = ChangeTelLogic::changemobile($request ["username"]);
        // 登录验证
        $obj_validate = new Validate ();
        $obj_validate->validateparam = array(
            array("input" => $request ["password"], "require" => "true", "message" => '密码不能为空'),
            array("input" => $request ["username"], "require" => "true", "message" => '用户名或密码不能为空')
        );
        $error = $obj_validate->validate();
        if ($error != '') {
            return $this->json($error,-1);
        }

        $model_member = new MemberModel();

        //账号或手机登陆
        $where = "(member_name='" . $request ['username'] . "' or member_mobile='" . $request ['username'] . "')";
        $where .= " and (member_passwd='" . md5($request ['password']) . "' ||  member_passwd='" . $request ['password'] . "')";

      
        $fields = "member_id,member_name,member_state,signature";
        $member_info = $model_member->getMemberInfo($where, $fields ,true);
        
        if (empty ($member_info)) {
            return $this->json('用户名密码错误',-1);
        }
        
        if (intval($member_info['member_state']) == 0) {
            return $this->json('您的账户已被冻结，若有疑问可致电股先生热线：400-9953366',-6);
        }

        if(empty($member_info['member_name'])){
            Log::record('查询member表时，member_name为空,用户ID：'.$member_info ['member_id']);
            return $this->json('登录失败，用户名为空',-1);
        }

        $token = $this->_get_token($member_info ['member_id'], $member_info ['member_name']);

        if (empty ($token)) {
            return $this->json('登录失败',-1);
        }

        if($request['route_mark']==''){
            
            $member_cache = ReLogic::rkcache ( 'user_tokens:'.$member_info ['member_id']);
        }else{
            $member_cache = ReLogic::rkcache ( $request['route_mark'].'_user_tokens:'.$member_info ['member_id']);
        }
        $member_cache = unserialize($member_cache);


        if(empty($member_cache['member_name'])){
            if($request['route_mark']==''){
                ReLogic::dkcache ( 'user_tokens:'.$member_info ['member_id']);
            }else{
                
                ReLogic::dkcache ( $request['route_mark'].'_user_tokens:'.$member_info ['member_id']);
            }

            $model_user_token=Model('user_token');
            $route_mark=$request['route_mark']!=''?trim($request['route_mark']):'app';
            $rs=$model_user_token->getOne('member_id='.$member_info ['member_id'].' and route_mark="'.$route_mark.'"');
            Log::record('查询redis时，member_name为空,用户ID:'.$member_info ['member_id'],'user_token表里member_name:'.$rs['member_name'].'redis数据'.print_r($member_cache,1));

            return $this->json('网络繁忙',-1);
        }

        /*$param = array();
        $param ['stage'] = 'newpush';//固定
        $param ['type'] = "login_out";
        $param ['object_type'] = "alias";
        $param['platform'] = json_encode(array("android", "ios"));
        $param['object'] = $member_info ['member_id'];//用户ID
        $param['content'] = "您的帐号已在别处登陆";
        $param['params']['type'] = 'login_out';//参数
        $param['params']['key'] = $token;//参数
        queue_ppush($param);*/

        $param = array();
        $param ['stage'] = 'update_login_time';//固定
        $param ['member_id'] = $member_info ['member_id'];
        $param ['time'] = time();
      //  $param ['ip']=getIp();
        
        ReLogic::queue_ppush($param);


        $rew = array(
            'member_id' => $member_info ['member_id'],
            'member_name' => $member_info ['member_name'],
            'key' => $token,
            //'member_avatar' => getAvatar($member_info ['member_id']),
            'signature' => $member_info ['signature']
        );

        //$SellerInfo = getSeller($member_info ['member_id']);
        $rew['fav_num'] = 0;
        $rew['type_id'] = 0;
        if($SellerInfo){
            $rew['fav_num'] = $SellerInfo['automatic_add_num']+$SellerInfo['fans_base_num']+ $SellerInfo['fav_num']*$SellerInfo['fans_n_num'];
            $rew['type_id'] = $SellerInfo['member_type'];
        }

        //TODO 2017年4月26日 16:12:38 兼容客服端密码
        if(strlen($request ['password']) == 32){

            $info = $model_member->where(['member_mobile'=>$request ['username'],'member_passwd'=>$request ['password']])->find();
            if(empty($info)){
                 $model_member->where(['member_mobile'=>$request ['username']])->update(['member_passwd'=>$request ['password']]);
            }
           
        }
        
        Log::record('login==>返给用户的数据：'.print_r($rew,true));
        return $this->json($rew);
    }

    /**
     * 用户注册
     *
     * @return mixed This is the return value description
     *
     */
    public function regOp()
    {
        // 注册验证
        $obj_validate = new Validate ();
        $obj_validate->validateparam = array(
            array("input" => $_REQUEST ["password"], "require" => "true", "message" => '密码不能为空'),
            array("input" => $_REQUEST ["code"], "require" => "true", "message" => '验证码不能为空'),
            array("input" => $_REQUEST ["password_confirm"], "require" => "true", "validator" => "Compare", "operator" => "==", "to" => $_REQUEST ["password"], "message" => '密码与确认密码不相同'),
            array("input" => $_REQUEST ["mobile"], "require" => "true", "validator" => "mobile", "message" => '手机号码格式不正确')
        );
        $error = $obj_validate->validate();
        if ($error != '') {
            output_error($error);
        }

        //判断短信验证码,是否错误
        $model_mobilecode = Model('mobilecode');
        $model_mobilecode->mobile = $_REQUEST ['mobile'];
        $model_mobilecode->action = 1;
        $Code_return = $model_mobilecode->access();
        //如果验证失败返回 失败原因
        if (isset ($Code_return ['error'])) {
            output_error($Code_return ['error']);
        }


        $model_pre_useranme = Model("pre_useranme");
        $map = array();
        $field = "username";
        $order  = 'RAND()';
        $pre_username_info = $model_pre_useranme->field($field)->order($order)->find();
        $pre_username = $pre_username_info['username'];
        $model_pre_useranme->where('username='.$pre_username)->delete();
        $pre_username = $pre_username ? 'g'.$pre_username : '';

        $inviterCode = !empty($_REQUEST ['inviter_code'])?$_REQUEST ['inviter_code']:$_REQUEST ['inviterCode'];
        $inviter_code = strval(trim($inviterCode));

        $info = $model_mobilecode->table('promotion_account')->where(array('promotion_code'=>$inviter_code))->field(array('id','phone'))->find();
        if(!empty($info['id'])){
            if($info['phone'] == trim($_REQUEST ["mobile"])){
                output_error('不可邀请自己');
            }
            $inviter_id = !empty($info['id'])?$info['id']:0;
            $inviter_type = 2;
        }else{
            $info = $model_mobilecode->table('member')->where(array('yaoqinghaoyou_share_num'=>$inviter_code))->field('member_id')->find();
            $inviter_id = !empty($info['member_id'])?$info['member_id']:0;
            $inviter_type = 1;
        }

        $model_member = Model('member');
        $register_info = array();
        $register_info ['username'] = $pre_username;
        $register_info ['password'] = $_REQUEST ['password'];
        $register_info ['password_confirm'] = $_REQUEST ['password_confirm'];
        $register_info ['member_mobile'] = $_REQUEST ['mobile'];
        $register_info ['inviter_id'] = $inviter_id;
        $register_info ['inviter_type'] = $inviter_id > 0? $inviter_type : 0;
        $register_info ['member_reg_client'] = empty($_REQUEST ['member_reg_client']) ? $this->useragent : trim($_REQUEST ['member_reg_client']);

        $member_info = $model_member->register($register_info);
        if (isset ($member_info ['error'])) {

            output_error($member_info ['error']);
        }
        
        $insert_username['member_name'] = $pre_username;

        //如果获取预用户名失败
        if(empty($pre_username))
        {
	        //生成用户名
	        $insert_username['member_name'] = 'g'.($member_info['member_id']+10000000);
	        $insert_where['member_id'] = $member_info['member_id'];
	        $model_member->editMember($insert_where, $insert_username);
        }
        $token = $this->_get_token($member_info ['member_id'], $insert_username['member_name']);
        if (empty ($token)) {
            output_error('注册失败');
        }
        $result ['member_id'] = $member_info ['member_id'];
        $result ['member_name'] = $insert_username['member_name'];
        $result ['key'] = $token;

        output_data($result);
    }

    /**乐股道用户注册
     * author xumin
     */
    public function reg_lgdOp()
    {
        // 注册验证
        $obj_validate = new Validate ();
        $obj_validate->validateparam = array(
            //array("input" => $_REQUEST ["password"], "require" => "true", "message" => '密码不能为空'),
            array("input" => $_REQUEST ["code"], "require" => "true", "message" => '验证码不能为空'),
            //array("input" => $_REQUEST ["password_confirm"], "require" => "true", "validator" => "Compare", "operator" => "==", "to" => $_REQUEST ["password"], "message" => '密码与确认密码不相同'),
            array("input" => $_REQUEST ["mobile"], "require" => "true", "validator" => "mobile", "message" => '手机号码格式不正确')
        );
        $error = $obj_validate->validate();
        if ($error != '') {
            output_error($error);
        }

        //判断短信验证码,是否错误
        $model_mobilecode = Model('mobilecode');
        $model_mobilecode->mobile = $_REQUEST ['mobile'];
        $model_mobilecode->action = 1;
        $Code_return = $model_mobilecode->access();
        //如果验证失败返回 失败原因
        if (isset ($Code_return ['error'])) {
            output_error($Code_return ['error']);
        }


        $model_pre_useranme = Model("pre_useranme");
        $map = array();
        $field = "username";
        $order  = 'RAND()';
        $pre_username_info = $model_pre_useranme->field($field)->order($order)->find();
        $pre_username = $pre_username_info['username'];
        $model_pre_useranme->where('username='.$pre_username)->delete();
        $pre_username = $pre_username ? 'g'.$pre_username : '';

        $inviterCode = !empty($_REQUEST ['inviter_code'])?$_REQUEST ['inviter_code']:$_REQUEST ['inviterCode'];
        $inviter_code = strval(trim($inviterCode));

        $info = $model_mobilecode->table('promotion_account')->where(array('promotion_code'=>$inviter_code))->field(array('id','phone'))->find();
        if(!empty($info['id'])){
            if($info['phone'] == trim($_REQUEST ["mobile"])){
                output_error('不可邀请自己');
            }
            $inviter_id = !empty($info['id'])?$info['id']:0;
            $inviter_type = 2;
        }else{
            $info = $model_mobilecode->table('member')->where(array('yaoqinghaoyou_share_num'=>$inviter_code))->field('member_id')->find();
            $inviter_id = !empty($info['member_id'])?$info['member_id']:0;
            $inviter_type = 1;
        }

        $model_member = Model('member');
        $register_info = array();
        $register_info ['username'] = $pre_username;
        $register_info ['password'] = rand(100000,999999);
        $register_info ['member_mobile'] = $_REQUEST ['mobile'];
        $register_info ['inviter_id'] = $inviter_id;
        $register_info ['inviter_type'] = $inviter_id > 0? $inviter_type : 0;
        $register_info ['member_reg_client'] = $this->useragent;

        $member_info = $model_member->register($register_info);
        if (isset ($member_info ['error'])) {

            output_error($member_info ['error']);
        }

        $insert_username['member_name'] = $pre_username;

        //如果获取预用户名失败
        if(empty($pre_username))
        {
            //生成用户名
            $insert_username['member_name'] = 'g'.($member_info['member_id']+10000000);
            $insert_where['member_id'] = $member_info['member_id'];
            $model_member->editMember($insert_where, $insert_username);
        }
        $token = $this->_get_token($member_info ['member_id'], $insert_username['member_name']);
        if (empty ($token)) {
            output_error('注册失败');
        }
        $result ['member_id'] = $member_info ['member_id'];
        $result ['member_name'] = $insert_username['member_name'];
        $result ['key'] = $token;

        output_data($result);
    }

}