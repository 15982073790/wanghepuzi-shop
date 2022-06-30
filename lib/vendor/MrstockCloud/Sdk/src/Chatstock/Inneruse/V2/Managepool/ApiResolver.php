<?php

namespace MrstockCloud\Chatstock\Inneruse\V2\Managepool;

use MrstockCloud\Client\Traits\ApiResolverTrait;

/**
 * Resolve Api based on the method name.
 *
 * @method Checkprivilegecode checkprivilegecode(array $options = []) whatFor="判断邀请码存不存在",codeMonkey="申长春" 
 * @method Getprivilegecode getprivilegecode(array $options = []) whatFor="随机获取邀请码",codeMonkey="申长春" 
 * @method Getadminservicetype getadminservicetype(array $options = []) whatFor="获取某个客服正在进行的服务类型",codeMonkey="申长春" 
 * @method Getotheradminid getotheradminid(array $options = []) whatFor="获取其他相同服务类型的客服",codeMonkey="申长春" 
 * @method Getadmincoustomer getadmincoustomer(array $options = []) whatFor=" admin_id的正在服务的客户.",codeMonkey="申长春" 
 * @method Getservicetypeadminid getservicetypeadminid(array $options = []) whatFor=" 获取服务类型下的所有admin_id.",codeMonkey="申长春" 
 * @method Getmemberadminbytype getmemberadminbytype(array $options = []) whatFor=" 根据memberid和servicetype获取他的adminid.",codeMonkey="申长春" 
 * @method Getmanagedatabumemberid getmanagedatabumemberid(array $options = []) whatFor=" 根据memberid和servicetype获取他的adminid.",codeMonkey="申长春" 

 */
class ApiResolver
{
    use ApiResolverTrait;
}
