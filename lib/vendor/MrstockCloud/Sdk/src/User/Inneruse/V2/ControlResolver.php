<?php

namespace MrstockCloud\User\Inneruse\V2;

use MrstockCloud\Client\Traits\ControlResolverTrait;

/**
 * Resolve Api based on the method name.
 *
 * @method Member\ApiResolver member() Member 控制器 
 * @method Membersearch\ApiResolver membersearch() Membersearch 控制器 
 * @method Test\ApiResolver test() Test 控制器 

 */
class ControlResolver
{
    use ControlResolverTrait;
}
