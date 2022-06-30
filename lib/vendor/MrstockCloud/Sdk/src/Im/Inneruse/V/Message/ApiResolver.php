<?php

namespace MrstockCloud\Im\Inneruse\V\Message;

use MrstockCloud\Client\Traits\ApiResolverTrait;

/**
 * Resolve Api based on the method name.
 *
 * @method Send send(array $options = []) whatFor="发送实时消息",menuName="消息相关",codeMonkey="" 
 * @method Receipt receipt(array $options = []) whatFor="消息回执",menuName="消息相关",codeMonkey="" 
 * @method Retract retract(array $options = []) whatFor="消息撤回",menuName="消息相关",codeMonkey="" 

 */
class ApiResolver
{
    use ApiResolverTrait;
}
