<?php

namespace MrstockCloud\Goods\Inneruse\V;

use MrstockCloud\Client\Traits\ControlResolverTrait;

/**
 * Resolve Api based on the method name.
 *
 * @method Activity\ApiResolver activity() Activity 控制器 
 * @method Goods\ApiResolver goods() Goods 控制器 

 */
class ControlResolver
{
    use ControlResolverTrait;
}
