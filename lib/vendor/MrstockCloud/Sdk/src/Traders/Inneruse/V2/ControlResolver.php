<?php

namespace MrstockCloud\Traders\Inneruse\V2;

use MrstockCloud\Client\Traits\ControlResolverTrait;

/**
 * Resolve Api based on the method name.
 *
 * @method Live\ApiResolver live() Live 控制器 

 */
class ControlResolver
{
    use ControlResolverTrait;
}
