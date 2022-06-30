<?php

namespace MrstockCloud\Im\Inneruse\V\Member;

use MrstockCloud\Client\Traits\ApiResolverTrait;

/**
 * Resolve Api based on the method name.
 *
 * @method Lasttalktime lasttalktime(array $options = []) whatFor="群成员最近发言时间",menuName="用户信息相关",codeMonkey=""

 */
class ApiResolver
{
    use ApiResolverTrait;
}
