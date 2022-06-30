<?php

namespace MrstockCloud\Leavingmessage\Inneruse\V2;

use MrstockCloud\Client\Traits\ControlResolverTrait;

/**
 * Resolve Api based on the method name.
 *
 * @method Comment\ApiResolver comment() Comment 控制器 
 * @method Traders\ApiResolver traders() Traders 控制器 

 */
class ControlResolver
{
    use ControlResolverTrait;
}
