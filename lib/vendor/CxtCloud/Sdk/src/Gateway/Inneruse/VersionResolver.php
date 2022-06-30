<?php

namespace CxtCloud\Gateway\Inneruse;

use MrstockCloud\Client\Traits\VersionResolverTrait;

/**
 * Find the specified version of the Gateway\Inneruse based on the method name as the version name.
 *
 * @package   CxtCloud\Gateway\Inneruse
 *
 * @method V\ControlResolver v() 控制器 V 版本 

 */
class VersionResolver
{
    use VersionResolverTrait;
}
