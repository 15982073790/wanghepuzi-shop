<?php

namespace CxtCloud\Community\Inneruse\V;

use MrstockCloud\Client\Traits\ControlResolverTrait;

/**
 * Resolve Api based on the method name.
 *
 * @method Community\ApiResolver community() Community 控制器 

 */
class ControlResolver
{
    use ControlResolverTrait;
}
