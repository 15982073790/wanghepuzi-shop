<?php

namespace MrstockCloud\Stock\Inneruse\V2;

use MrstockCloud\Client\Traits\ControlResolverTrait;

/**
 * Resolve Api based on the method name.
 *
 * @method Stock\ApiResolver stock() Stock 控制器 

 */
class ControlResolver
{
    use ControlResolverTrait;
}
