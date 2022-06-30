<?php

namespace MrstockCloud\Hq\Inneruse\V2;

use MrstockCloud\Client\Traits\ControlResolverTrait;

/**
 * Resolve Api based on the method name.
 *
 * @method Index\ApiResolver index() Index 控制器 
 * @method Stock\ApiResolver stock() Stock 控制器 
 * @method Stockchange\ApiResolver stockchange() Stockchange 控制器 
 * @method Stockfinance\ApiResolver stockfinance() Stockfinance 控制器 

 */
class ControlResolver
{
    use ControlResolverTrait;
}
