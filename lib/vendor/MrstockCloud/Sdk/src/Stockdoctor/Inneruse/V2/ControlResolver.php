<?php

namespace MrstockCloud\Stockdoctor\Inneruse\V2;

use MrstockCloud\Client\Traits\ControlResolverTrait;

/**
 * Resolve Api based on the method name.
 *
 * @method Help\ApiResolver help() Help 控制器 
 * @method Teacher\ApiResolver teacher() Teacher 控制器 

 */
class ControlResolver
{
    use ControlResolverTrait;
}
