<?php

namespace CxtCloud\Secret\Inneruse\V;

use MrstockCloud\Client\Traits\ControlResolverTrait;

/**
 * Resolve Api based on the method name.
 *
 * @method SecretSingle\ApiResolver secretSingle() SecretSingle 控制器 
 * @method Secretmobile\ApiResolver secretmobile() Secretmobile 控制器 

 */
class ControlResolver
{
    use ControlResolverTrait;
}
