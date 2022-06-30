<?php

namespace MrstockCloud\Mystock\Inneruse\V;

use MrstockCloud\Client\Traits\ControlResolverTrait;

/**
 * Resolve Api based on the method name.
 *
 * @method Group\ApiResolver group() Group 控制器 

 */
class ControlResolver
{
    use ControlResolverTrait;
}
