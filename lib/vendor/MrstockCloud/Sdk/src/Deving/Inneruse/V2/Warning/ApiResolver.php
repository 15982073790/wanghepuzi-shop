<?php

namespace MrstockCloud\Deving\Inneruse\v2\Warning;

use MrstockCloud\Client\Traits\ApiResolverTrait;

/**
 * Resolve Api based on the method name.
 *
 * @method Lists lists(array $options = []) whatFor="我的预警",menuName="我的预警",codeMonkey=""
 * @method Deletewarning deletewarning(array $options = [])  

 */
class ApiResolver
{
    use ApiResolverTrait;
}
