<?php

namespace MrstockCloud\Chatstock\Inneruse\V;

use MrstockCloud\Client\Traits\ControlResolverTrait;

/**
 * Resolve Api based on the method name.
 *
 * @method Group\ApiResolver group() Group 控制器 
 * @method Managepool\ApiResolver managepool() Managepool 控制器 
 * @method Member\ApiResolver member() Member 控制器 
 * @method Team\ApiResolver team() Team 控制器 

 */
class ControlResolver
{
    use ControlResolverTrait;
}
