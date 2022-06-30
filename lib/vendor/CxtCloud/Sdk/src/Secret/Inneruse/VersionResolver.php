<?php

namespace CxtCloud\Secret\Inneruse;

use MrstockCloud\Client\Traits\VersionResolverTrait;

/**
 * Find the specified version of the Secret\Inneruse based on the method name as the version name.
 *
 * @package   CxtCloud\Secret\Inneruse
 *
 * @method V\ControlResolver v() 控制器 V 版本 

 */
class VersionResolver
{
    use VersionResolverTrait;
}
