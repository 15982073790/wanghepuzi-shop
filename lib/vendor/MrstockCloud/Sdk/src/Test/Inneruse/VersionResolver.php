<?php

namespace MrstockCloud\Test\Inneruse;

use MrstockCloud\Client\Traits\VersionResolverTrait;

/**
 * Find the specified version of the Test\Inneruse based on the method name as the version name.
 *
 * @package   MrstockCloud\Test\Inneruse
 *
 * @method V\ControlResolver v() 控制器 V 版本 

*/
class VersionResolver
{
    use VersionResolverTrait;
}
