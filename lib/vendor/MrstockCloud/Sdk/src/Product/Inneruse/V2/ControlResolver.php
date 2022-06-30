<?php

namespace MrstockCloud\Product\Inneruse\V2;

use MrstockCloud\Client\Traits\ControlResolverTrait;

/**
 * Resolve Api based on the method name.
 *
 * @method Product\ApiResolver product() Product 控制器 
 * @method ProductTeam\ApiResolver productTeam() ProductTeam 控制器 

 */
class ControlResolver
{
    use ControlResolverTrait;
}
