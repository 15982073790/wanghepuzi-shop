<?php

namespace CxtCloud\Course\Inneruse\V;

use MrstockCloud\Client\Traits\ControlResolverTrait;

/**
 * Resolve Api based on the method name.
 *
 * @method Course\ApiResolver course() Course 控制器 
 * @method CourseVideo\ApiResolver courseVideo() CourseVideo 控制器 

 */
class ControlResolver
{
    use ControlResolverTrait;
}
