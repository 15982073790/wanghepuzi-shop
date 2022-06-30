<?php

namespace CxtCloud\Uploadfile\Inneruse\V;

use MrstockCloud\Client\Traits\ControlResolverTrait;

/**
 * Resolve Api based on the method name.
 *
 * @method Ftpupload\ApiResolver ftpupload() Ftpupload 控制器 
 * @method Uploadfile\ApiResolver uploadfile() Uploadfile 控制器 

 */
class ControlResolver
{
    use ControlResolverTrait;
}
