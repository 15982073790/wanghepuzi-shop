<?php

namespace MrstockCloud\Customer\Inneruse\V;

use MrstockCloud\Client\Traits\ControlResolverTrait;

/**
 * Resolve Api based on the method name.
 *
 * @method BatchService\ApiResolver batchService() BatchService 控制器 

 */
class ControlResolver
{
    use ControlResolverTrait;
}
