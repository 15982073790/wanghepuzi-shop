<?php

namespace MrstockCloud\Deving\Inneruse\V;

use MrstockCloud\Client\Traits\ControlResolverTrait;

/**
 * Resolve Api based on the method name.
 *
 * @method Warning\ApiResolver warning() Warning 控制器 

 */
class ControlResolver
{
    use ControlResolverTrait;
}
