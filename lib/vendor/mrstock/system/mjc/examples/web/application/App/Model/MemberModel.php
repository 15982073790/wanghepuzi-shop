<?php
namespace App\Model;

use MrStock\System\Orm\Model;

class MemberModel extends Model {

    public function __construct(){
        parent::__construct('member');
    }

    /**
     * 会员详细信息（查库）
     * @param array $condition
     * @param string $field
     * @return array
     */
    public function getMemberInfo($condition, $field = '*', $master = false) {

        return $this->table('member')->field($field)->where($condition)->master($master)->find();
    }

	/**验证用户是否有 第三方机构 下服务模块的权限
	 * @access public
	 * @param $condition
	 * @author tangjun <469307838@qq.com>
	 * @date 2016年4月22日 15:02:47
	 * @copyright
	 */
	public function checkAgencyPermissions($condition){

		$param = array();
		$param['table'] = 'agency,agency_service_class,agency_service_class_member';
		$param['where'] = 'agency.agency_id = '.$condition['agency_id'].' and agency_service_class_member.member_id = '.$condition['member_id'].' and  agency_service_class_member.state = '.$condition['state'];
		$param['join_type'] = 'LEFT JOIN';
		$param['join_on'] = array(
			'agency.agency_id = agency_service_class.agency_id',
			'agency_service_class.asc_id = agency_service_class_member.asc_id',
		);
		$param['field'] = 'agency.agency_id';
		$param['limit'] = 1;
		$result = Db::select($param);
		if(!empty($result) && count($result)>0){
			return true;
		}else{
			return	false;
		}
	}

    /**
     * 
     * 验证会员的第三方权限
     * @param unknown_type $condition
     * @param unknown_type $field
     * @param unknown_type $master
     */
	public function Check_Member_service($condition, $field = '*', $master = false) {
        $member_service = $this->table('agency_service_class_member')->field($field)->where($condition)->master($master)->find();
        if(is_array($member_service) && count($member_service)>0)
        {
        	return true;
        }
        return FALSE;
    }

    /**
     * 取得会员详细信息（优先查询缓存）
     * 如果未找到，则缓存所有字段
     * @param int $member_id
     * @param string $field 需要取得的缓存键值, 例如：'*','member_name,member_sex'
     * @return array
     */
    public function getMemberInfoByID($member_id, $fields = '*') {
		//$cache_member_info = rcache("members", '', $fields);
		$member_info = rkcache("members:".$member_id);
		//$cache_member_info = $cache_member_info[$member_id];
		//$member_info = unserialize($cache_member_info);
		if (empty($member_info)) {
			$member_info = $this->getMemberInfo(array('member_id'=>$member_id),'*',true);
			if(is_array($member_info) && count($member_info)>0)
			{
				wkcache("members:".$member_id, $member_info,86400);
			}
		}
		return $member_info;
    }

    /**
     * 编辑会员
     * @param array $condition
     * @param array $data
     */
    public function editMember($condition, $data) {
        $update = $this->table('member')->where($condition)->update($data);
        if ($update && $condition['member_id']) {
            //dcache("members","", intval($condition['member_id']));
			dkcache("members:".$condition['member_id']);
        }
        return $update;
    }

    /**
     * 注册
     */
    public function register($register_info) {

        // 验证手机号码是否重复
		$check_member_name	= $this->getMemberInfo(array('member_mobile'=>$register_info['member_mobile']));
		if(is_array($check_member_name) and count($check_member_name) > 0) {
			return array('error' => '手机号码已存在');
		}

		// 会员添加
		$member_info	= array();
		$member_info['member_name']		= $register_info['username'];
		$member_info['member_passwd']		= $register_info['password'];
		$member_info['member_mobile']		= $register_info['member_mobile'];
		$member_info['member_reg_client']	= $register_info['member_reg_client'];
		$member_info['inviter_type']	= $register_info['inviter_type'];
        $member_info['member_type']	= $register_info['member_type'] ? $register_info['member_type'] : 0;

		//注册策略 是否有经验

		//添加邀请人 是否异步 
		$member_info['inviter_id']		= $register_info['inviter_id'];
		
		$insert_id	= $this->addMember($member_info);
		
		if(isset($insert_id['error']))
		{
			return $insert_id;
		}
		else
		{
			$member_info['member_id'] = $insert_id;
			return $member_info;
		}

    }

	/**
	 * 注册会员
	 *
	 * @param	array $param 会员信息
	 * @return	array 数组格式的返回结果
	 */
	public function addMember($param) {

		$member_info	= array();
		$member_info['member_id']				= $param['member_id'];
		$member_info['member_name']			= $param['member_name'];
        //TODO htx 2017年4月27日 15:58:38 兼容客服端密码
		if(strlen(trim($param['member_passwd']))==32){
            $member_info['member_passwd']			= trim($param['member_passwd']);
        }else{
            $member_info['member_passwd']			= md5(trim($param['member_passwd']));
        }
        $member_info['member_mobile']			= $param['member_mobile'];
		$member_info['member_time']			= time();
		$member_info['member_login_time'] 	= time();
		$member_info['member_old_login_time'] = time();
		$member_info['member_login_ip']		= getIp();
		$member_info['member_old_login_ip']	= $member_info['member_login_ip'];
		$member_info['inviter_type']	= $param['inviter_type'];
		$member_info['member_reg_client']		= $param['member_reg_client'];
		$member_info['member_group_id']		= 1;
        $member_info['member_type']			= $param['member_type'];

		//添加邀请人(推荐人)
		$member_info['inviter_id']	       = $param['inviter_id'];
		$insert_id	= $this->table('member')->insert($member_info);
		if (!$insert_id) {
			return array('error' => '注册失败');
		}
		return $insert_id;
	}


	public function addMemberZxg($mobile){
		// 验证手机号码是否重复
		$is_member = 2;
		$check_member_name	= $this->getMemberInfo(array('member_mobile'=>$mobile));
		if(is_array($check_member_name) and count($check_member_name) > 0) {
			$is_member = 1;
		}else{
			$model_pre_useranme = Model("pre_useranme");
			$field = "username";
			$order  = 'RAND()';
			$pre_username_info = $model_pre_useranme->field($field)->order($order)->find();
			$pre_username = $pre_username_info['username'];
			$model_pre_useranme->where('username='.$pre_username)->delete();
			$pre_username = $pre_username ? 'g'.$pre_username : '';
			$model_member = Model('member');
			$register_info = array();
			$register_info ['username'] = $pre_username;
			$register_info ['password'] = md5(substr($mobile,-6));
			$register_info ['member_mobile'] = $mobile;
			$register_info['member_reg_client'] = $_REQUEST['client_type'];//财学堂注册途径

			$member_info = $model_member->register($register_info);
			if (isset ($member_info ['error'])) {
				output_error($member_info ['error']);
			}
			$insert_username['member_name'] = $pre_username;
			//如果获取预用户名失败
			if(empty($pre_username)){
				//生成用户名
				$insert_username['member_name'] = 'g'.($member_info['member_id']+10000000);
				$insert_where['member_id'] = $member_info['member_id'];
				$model_member->editMember($insert_where, $insert_username);
			}
			return $is_member;






		}
		return $is_member;

	}

	
	/**
	 * 根据手机号修改密码
	 *
	 * @param mixed $update_info This is a description
	 * @return mixed This is the return value description
	 *
	 */	
	public function update_member_pwd($update_info){

		$map = array('member_mobile'=>$update_info["member_mobile"]);
		
		// 检查用户名和手机是否匹配
		$check_member_name	= $this->getMemberInfo($map);
		if(!is_array($check_member_name)) {
			return array('error' => '手机号不存在');
		}

		//TODO 2017年4月26日 16:12:38 兼容客服端密码
		if(strlen($update_info["password"]) == 32){
			$member_passwd = $update_info["password"];
		}else{
			$member_passwd = md5($update_info["password"]);
		}
		

		$update_return = $this->editMember(array('member_id'=>$check_member_name['member_id']),array('member_passwd'=>$member_passwd));
		if($update_return){
			return $update_return;
		}else{
			return array('error' => '修改密码失败');
		}
	}
}