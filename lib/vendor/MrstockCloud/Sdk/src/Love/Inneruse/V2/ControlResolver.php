<?php

namespace MrstockCloud\Love\Inneruse\V2;

use MrstockCloud\Client\Traits\ControlResolverTrait;

/**
 * Resolve Api based on the method name.
 *
 * @method Attention\ApiResolver attention() Attention 控制器 
 * @method Favorites\ApiResolver favorites() Favorites 控制器 
 * @method Up\ApiResolver up() Up 控制器 

 */
class ControlResolver
{
    use ControlResolverTrait;
}
