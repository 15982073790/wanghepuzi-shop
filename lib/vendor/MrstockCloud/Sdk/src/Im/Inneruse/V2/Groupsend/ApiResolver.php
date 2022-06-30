<?php

namespace MrstockCloud\Im\Inneruse\V2\Groupsend;

use MrstockCloud\Client\Traits\ApiResolverTrait;

/**
 * Resolve Api based on the method name.
 *
 * @method Getlist getlist(array $options = []) whatFor="群发消息列表",menuName="群发消息列表",codeMonkey="" 
 * @method Getdetail getdetail(array $options = []) whatFor="群发消息详细",menuName="群发消息详细",codeMonkey="" 
 * @method Gsretract gsretract(array $options = []) whatFor="群发消息撤回",menuName="群发消息撤回",codeMonkey="" 

 */
class ApiResolver
{
    use ApiResolverTrait;
}
