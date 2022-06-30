<?php

namespace MrstockCloud\Institution\Inneruse\V2;

use MrstockCloud\Client\Traits\ControlResolverTrait;

/**
 * Resolve Api based on the method name.
 *
 * @method Company\ApiResolver company() Company 控制器 
 * @method Groupframwork\ApiResolver groupframwork() Groupframwork 控制器 
 * @method Institution\ApiResolver institution() Institution 控制器 

 */
class ControlResolver
{
    use ControlResolverTrait;
}
