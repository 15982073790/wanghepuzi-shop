<?php

namespace MrstockCloud\Im\Inneruse\V2\User;

use MrstockCloud\Client\Traits\ApiResolverTrait;

/**
 * Resolve Api based on the method name.
 *
 * @method Login login(array $options = []) whatFor="用户登陆",menuName="用户动作相关",codeMonkey=""
 * @method Isuidonline isuidonline(array $options = [])  

 */
class ApiResolver
{
    use ApiResolverTrait;
}
