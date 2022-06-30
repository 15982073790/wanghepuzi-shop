<?php

namespace MrstockCloud\Sysconfig\Inneruse\V2;

use MrstockCloud\Client\Traits\ControlResolverTrait;

/**
 * Resolve Api based on the method name.
 *
 * @method Dictionary\ApiResolver dictionary() Dictionary 控制器 
 * @method Setting\ApiResolver setting() Setting 控制器 
 * @method TextSupport\ApiResolver textSupport() TextSupport 控制器 

 */
class ControlResolver
{
    use ControlResolverTrait;
}
