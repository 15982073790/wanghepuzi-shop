<?php

namespace MrstockCloud\Pay\Inneruse\V;

use MrstockCloud\Client\Traits\ControlResolverTrait;

/**
 * Resolve Api based on the method name.
 *
 * @method Buyoff\ApiResolver buyoff() Buyoff 控制器 
 * @method Crmapi\ApiResolver crmapi() Crmapi 控制器 
 * @method Order\ApiResolver order() Order 控制器 
 * @method Redbag\ApiResolver redbag() Redbag 控制器 

 */
class ControlResolver
{
    use ControlResolverTrait;
}
