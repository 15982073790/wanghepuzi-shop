<?php

namespace MrstockCloud\News\Inneruse\V2;

use MrstockCloud\Client\Traits\ControlResolverTrait;

/**
 * Resolve Api based on the method name.
 *
 * @method Article\ApiResolver article() Article 控制器 

 */
class ControlResolver
{
    use ControlResolverTrait;
}
