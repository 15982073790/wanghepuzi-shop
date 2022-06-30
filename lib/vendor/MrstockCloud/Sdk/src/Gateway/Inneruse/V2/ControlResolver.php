<?php

namespace MrstockCloud\Gateway\Inneruse\V2;

use MrstockCloud\Client\Traits\ControlResolverTrait;

/**
 * Resolve Api based on the method name.
 *
 * @method Admin\ApiResolver admin() Admin 控制器 
 * @method Adminsearch\ApiResolver adminsearch() Adminsearch 控制器 
 * @method Auth\ApiResolver auth() Auth 控制器 
 * @method Test\ApiResolver test() Test 控制器 

 */
class ControlResolver
{
    use ControlResolverTrait;
}
