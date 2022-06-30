<?php

namespace MrstockCloud\Solution\Inneruse\V;

use MrstockCloud\Client\Traits\ControlResolverTrait;

/**
 * Resolve Api based on the method name.
 *
 * @method Live\ApiResolver live() Live 控制器 
 * @method Teacher\ApiResolver teacher() Teacher 控制器 

 */
class ControlResolver
{
    use ControlResolverTrait;
}
