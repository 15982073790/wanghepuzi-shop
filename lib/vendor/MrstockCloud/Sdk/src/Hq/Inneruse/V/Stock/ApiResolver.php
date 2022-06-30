<?php

namespace MrstockCloud\Hq\Inneruse\V\Stock;

use MrstockCloud\Client\Traits\ApiResolverTrait;

/**
 * Resolve Api based on the method name.
 *
 * @method Getlist getlist(array $options = []) whatFor="批量获取股票信息",menuName="",codeMonkey=""

 */
class ApiResolver
{
    use ApiResolverTrait;
}
