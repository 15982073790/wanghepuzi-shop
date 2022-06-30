<?php

namespace MrstockCloud\Message\Inneruse\V2;

use MrstockCloud\Client\Traits\ControlResolverTrait;

/**
 * Resolve Api based on the method name.
 *
 * @method Access\ApiResolver access() Access 控制器 

 */
class ControlResolver
{
    use ControlResolverTrait;
}
