<?php

namespace MrstockCloud\Chatstock\Inneruse\V\Member;

use MrstockCloud\Client\Traits\ApiResolverTrait;

/**
 * Resolve Api based on the method name.
 *
 * @method Assinfo assinfo(array $options = []) whatFor="用户所属信息",menuName="用户所属信息",codeMonkey=""
 * @method Changerelate changerelate(array $options = [])  

 */
class ApiResolver
{
    use ApiResolverTrait;
}
