<?php

namespace MrstockCloud\Customer\Inneruse;

use MrstockCloud\Client\Traits\VersionResolverTrait;

/**
 * Find the specified version of the Customer\Inneruse based on the method name as the version name.
 *
 * @package   MrstockCloud\Customer\Inneruse
 *
 * @method V\ControlResolver v() 控制器 V 版本 

  * @method V2\ControlResolver v2() 控制器 V2 版本 

*/
class VersionResolver
{
    use VersionResolverTrait;
}
//11
