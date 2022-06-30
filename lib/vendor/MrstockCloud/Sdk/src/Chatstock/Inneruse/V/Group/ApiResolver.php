<?php

namespace MrstockCloud\Chatstock\Inneruse\V\Group;

use MrstockCloud\Client\Traits\ApiResolverTrait;

/**
 * Resolve Api based on the method name.
 *
 * @method Addteam addteam(array $options = []) whatFor="用户进入服务群",menuName="进入服务群",codeMonkey="" 
 * @method Interaddteam interaddteam(array $options = []) whatFor="用户邀请码进入服务群",menuName="用户邀请码进入服务群",codeMonkey="" 

 */
class ApiResolver
{
    use ApiResolverTrait;
}
