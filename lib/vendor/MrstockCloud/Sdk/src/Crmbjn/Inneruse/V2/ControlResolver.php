<?php

namespace MrstockCloud\Crmbjn\Inneruse\V2;

use MrstockCloud\Client\Traits\ControlResolverTrait;

/**
 * Resolve Api based on the method name.
 *
 * @method Eventbuygoodsb\ApiResolver eventbuygoodsb() Eventbuygoodsb 控制器 
 * @method Managepool\ApiResolver managepool() Managepool 控制器 
 * @method Member\ApiResolver member() Member 控制器 
 * @method Membersalesman\ApiResolver membersalesman() Membersalesman 控制器 
 * @method Ms\ApiResolver ms() Ms 控制器 

 */
class ControlResolver
{
    use ControlResolverTrait;
}
