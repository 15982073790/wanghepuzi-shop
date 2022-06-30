<?php

namespace MrstockCloud\Chatstock\Inneruse\V2;

use MrstockCloud\Client\Traits\ControlResolverTrait;

/**
 * Resolve Api based on the method name.
 *
 * @method Adminchat\ApiResolver adminchat() Adminchat 控制器 
 * @method Chatmsg\ApiResolver chatmsg() Chatmsg 控制器 
 * @method Fuwuhao\ApiResolver fuwuhao() Fuwuhao 控制器 
 * @method Managepool\ApiResolver managepool() Managepool 控制器 
 * @method Member\ApiResolver member() Member 控制器 
 * @method Team\ApiResolver team() Team 控制器 

 */
class ControlResolver
{
    use ControlResolverTrait;
}
