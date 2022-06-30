<?php

namespace MrstockCloud\Compliance\Inneruse\V;

use MrstockCloud\Client\Traits\ControlResolverTrait;

/**
 * Resolve Api based on the method name.
 *
 * @method Contract\ApiResolver contract() Contract 控制器 
 * @method Member\ApiResolver member() Member 控制器 

 */
class ControlResolver
{
    use ControlResolverTrait;
}
