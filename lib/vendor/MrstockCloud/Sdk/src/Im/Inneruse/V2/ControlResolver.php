<?php

namespace MrstockCloud\Im\Inneruse\V2;

use MrstockCloud\Client\Traits\ControlResolverTrait;

/**
 * Resolve Api based on the method name.
 *
 * @method Dialogbox\ApiResolver dialogbox() Dialogbox 控制器 
 * @method Dynamic\ApiResolver dynamic() Dynamic 控制器 
 * @method Group\ApiResolver group() Group 控制器 
 * @method Groupsend\ApiResolver groupsend() Groupsend 控制器 
 * @method Groupuser\ApiResolver groupuser() Groupuser 控制器 
 * @method Liveroom\ApiResolver liveroom() Liveroom 控制器 
 * @method Member\ApiResolver member() Member 控制器 
 * @method Message\ApiResolver message() Message 控制器 
 * @method Messageextra\ApiResolver messageextra() Messageextra 控制器 
 * @method Taguser\ApiResolver taguser() Taguser 控制器 
 * @method Tcp\ApiResolver tcp() Tcp 控制器 
 * @method User\ApiResolver user() User 控制器 

 */
class ControlResolver
{
    use ControlResolverTrait;
}
