<?php

namespace MrstockCloud\Membergoods\Inneruse\V;

use MrstockCloud\Client\Traits\ControlResolverTrait;

/**
 * Resolve Api based on the method name.
 *
 * @method Customer\ApiResolver customer() Customer 控制器 
 * @method Membergoods\ApiResolver membergoods() Membergoods 控制器 

 */
class ControlResolver
{
    use ControlResolverTrait;
}
