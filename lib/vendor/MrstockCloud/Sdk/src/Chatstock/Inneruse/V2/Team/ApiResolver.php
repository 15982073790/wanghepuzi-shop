<?php

namespace MrstockCloud\Chatstock\Inneruse\V2\Team;

use MrstockCloud\Client\Traits\ApiResolverTrait;

/**
 * Resolve Api based on the method name.
 *
 * @method Teamlist teamlist(array $options = []) whatFor="团队列表",menuName="团队列表",codeMonkey="" 
 * @method Teaminfo teaminfo(array $options = []) whatFor="团队详情",menuName="团队详情",codeMonkey="" 
 * @method Ischatstock ischatstock(array $options = []) whatFor="老师是否有聊股",menuName="老师是否有聊股",codeMonkey="" 
 * @method Adminforteam adminforteam(array $options = []) whatFor="老师或助理所属群",menuName="老师或助理所属群",codeMonkey="" 
 * @method Getadmininfo getadmininfo(array $options = []) whatFor="根据老师或助理id，获取所属身份",menuName="根据老师或助理id，获取所属身份",codeMonkey="wangsongqing" 
 * @method Adminformember adminformember(array $options = []) whatFor="后台账号所有客户",menuName="所有客户",codeMonkey="" 
 * @method Allteacher allteacher(array $options = []) whatFor="后台账号所有已进群老师或助理",menuName="",codeMonkey="" 

 */
class ApiResolver
{
    use ApiResolverTrait;
}
