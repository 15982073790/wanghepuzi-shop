<?php

namespace CxtCloud\Bean\Inneruse;

use MrstockCloud\Client\Traits\VersionResolverTrait;

/**
 * Find the specified version of the Bean\Inneruse based on the method name as the version name.
 *
 * @package   CxtCloud\Bean\Inneruse
 *
 * @method V\ControlResolver v() 控制器 V 版本 

 */
class VersionResolver
{
    use VersionResolverTrait;
}
