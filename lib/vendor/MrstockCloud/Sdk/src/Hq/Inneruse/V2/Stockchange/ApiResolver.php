<?php

namespace MrstockCloud\Hq\Inneruse\V2\Stockchange;

use MrstockCloud\Client\Traits\ApiResolverTrait;

/**
 * Resolve Api based on the method name.
 *
 * @method Changeadd changeadd(array $options = [])  
 * @method Listinfo listinfo(array $options = [])  
 * @method Changestatus changestatus(array $options = [])  
 * @method Getotherstock getotherstock(array $options = [])  

 */
class ApiResolver
{
    use ApiResolverTrait;
}
