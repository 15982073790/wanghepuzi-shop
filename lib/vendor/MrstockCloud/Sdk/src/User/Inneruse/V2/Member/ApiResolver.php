<?php

namespace MrstockCloud\User\Inneruse\V2\Member;

use MrstockCloud\Client\Traits\ApiResolverTrait;

/**
 * Resolve Api based on the method name.
 *
 * @method Info_by_member_id info_by_member_id(array $options = []) whatFor="根据用户ID集合获取用户信息",menuName="",codeMonkey="tongyanming" 
 * @method Member_info member_info(array $options = []) whatFor="获取用户信息",menuName="",codeMonkey="tongyanming" 
 * @method Memberinfo memberinfo(array $options = [])  
 * @method Info_by_mobile_en info_by_mobile_en(array $options = []) whatFor="根据mobile（加密）集合获取用户信息",menuName="",codeMonkey="tongyanming" 
 * @method Add_real_name add_real_name(array $options = []) whatFor="某些场景下用户录入真实姓名请调用此接口写入member表",menuName="添加用户真实姓名",codeMonkey="tongyanming" 
 * @method Member_info_by_inviter_code member_info_by_inviter_code(array $options = []) whatFor="通过邀请码获取关联用户的信息",menuName="",codeMonkey="童彦铭" 
 * @method Get get(array $options = []) whatFor="登录验证",menuName="",codeMonkey="童彦铭" 
 * @method Changeinvitecode changeinvitecode(array $options = [])  
 * @method Getmemberid getmemberid(array $options = [])  
 * @method Synmobileandinvitercode synmobileandinvitercode(array $options = [])  
 * @method Getlist getlist(array $options = [])  
 * @method Checkmobilecode checkmobilecode(array $options = [])  

 */
class ApiResolver
{
    use ApiResolverTrait;
}
