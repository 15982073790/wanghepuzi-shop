<?php

namespace CxtCloud\Bean\Inneruse\V;

use MrstockCloud\Client\Traits\ControlResolverTrait;

/**
 * Resolve Api based on the method name.
 *
 * @method BeanRule\ApiResolver beanRule() BeanRule 控制器 
 * @method MemberBean\ApiResolver memberBean() MemberBean 控制器 

 */
class ControlResolver
{
    use ControlResolverTrait;
}
