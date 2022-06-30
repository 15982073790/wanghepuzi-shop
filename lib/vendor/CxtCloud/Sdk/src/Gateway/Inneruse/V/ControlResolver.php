<?php

namespace CxtCloud\Gateway\Inneruse\V;

use MrstockCloud\Client\Traits\ControlResolverTrait;

/**
 * Resolve Api based on the method name.
 *
 * @method Admin\ApiResolver admin() Admin 控制器 
 * @method Auth\ApiResolver auth() Auth 控制器 

 */
class ControlResolver
{
    use ControlResolverTrait;
}
