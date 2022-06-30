<?php

namespace MrstockCloud\Compliance\Inneruse\V2;

use MrstockCloud\Client\Traits\ControlResolverTrait;

/**
 * Resolve Api based on the method name.
 *
 * @method Contract\ApiResolver contract() Contract 控制器 
 * @method Member\ApiResolver member() Member 控制器 
 * @method Memberstocks\ApiResolver memberstocks() Memberstocks 控制器 
 * @method MiexVideo\ApiResolver miexVideo() MiexVideo 控制器 
 * @method Program\ApiResolver program() Program 控制器 

 */
class ControlResolver
{
    use ControlResolverTrait;
}
