<?php

namespace MrstockCloud\Chatstock\Inneruse\V\Team;

use MrstockCloud\Client\Traits\ApiResolverTrait;

/**
 * Resolve Api based on the method name.
 *
 * @method Teamlist teamlist(array $options = []) whatFor="团队列表",menuName="团队列表",codeMonkey="" 
 * @method Teaminfo teaminfo(array $options = []) whatFor="团队详情",menuName="团队详情",codeMonkey="" 
 * @method Ischatstock ischatstock(array $options = []) whatFor="老师是否有聊股",menuName="老师是否有聊股",codeMonkey="" 
 * @method Adminforteam adminforteam(array $options = []) whatFor="老师或助理所属群",menuName="老师或助理所属群",codeMonkey="" 

 */
class ApiResolver
{
    use ApiResolverTrait;
}
