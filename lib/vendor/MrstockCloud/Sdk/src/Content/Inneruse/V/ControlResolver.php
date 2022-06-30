<?php

namespace MrstockCloud\Content\Inneruse\V;

use MrstockCloud\Client\Traits\ControlResolverTrait;

/**
 * Resolve Api based on the method name.
 *
 * @method Chance\ApiResolver chance() Chance 控制器 
 * @method Team\ApiResolver team() Team 控制器 

 */
class ControlResolver
{
    use ControlResolverTrait;
}
