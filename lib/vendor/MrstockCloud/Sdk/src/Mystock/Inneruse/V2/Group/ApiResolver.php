<?php

namespace MrstockCloud\Mystock\Inneruse\V2\Group;

use MrstockCloud\Client\Traits\ApiResolverTrait;

/**
 * Resolve Api based on the method name.
 *
 * @method Checkstock checkstock(array $options = []) whatFor="检测该支股票是否属于自选股",codeMonkey=""

 */
class ApiResolver
{
    use ApiResolverTrait;
}
