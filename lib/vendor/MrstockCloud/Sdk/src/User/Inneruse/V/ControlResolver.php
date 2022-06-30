<?php

namespace MrstockCloud\User\Inneruse\V;

use MrstockCloud\Client\Traits\ControlResolverTrait;

/**
 * Resolve Api based on the method name.
 *
 * @method Member\ApiResolver member() Member 控制器 
 * @method Test\ApiResolver test() Test 控制器 

 */
class ControlResolver
{
    use ControlResolverTrait;
}
