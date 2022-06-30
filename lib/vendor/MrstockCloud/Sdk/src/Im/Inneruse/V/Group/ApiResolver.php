<?php

namespace MrstockCloud\Im\Inneruse\V\Group;

use MrstockCloud\Client\Traits\ApiResolverTrait;

/**
 * Resolve Api based on the method name.
 *
 * @method Create create(array $options = []) whatFor="创建群",menuName="群相关",codeMonkey="" 
 * @method Dismiss dismiss(array $options = []) whatFor="解散群",menuName="群相关",codeMonkey="" 

 */
class ApiResolver
{
    use ApiResolverTrait;
}
