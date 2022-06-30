<?php

namespace MrstockCloud\Test\Inneruse\V;

use MrstockCloud\Client\Traits\ControlResolverTrait;

/**
 * Resolve Api based on the method name.
 *
 * @method Ceshi\ApiResolver test() Ceshi 控制器 

 */
class ControlResolver
{
    use ControlResolverTrait;
}
