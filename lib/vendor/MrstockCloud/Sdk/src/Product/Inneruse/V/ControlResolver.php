<?php

namespace MrstockCloud\Product\Inneruse\V;

use MrstockCloud\Client\Traits\ControlResolverTrait;

/**
 * Resolve Api based on the method name.
 *
 * @method Product\ApiResolver product() Product 控制器 

 */
class ControlResolver
{
    use ControlResolverTrait;
}
