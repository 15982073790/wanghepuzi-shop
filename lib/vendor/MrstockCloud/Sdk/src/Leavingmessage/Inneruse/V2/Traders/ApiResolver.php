<?php

namespace MrstockCloud\Leavingmessage\Inneruse\V2\Traders;

use MrstockCloud\Client\Traits\ApiResolverTrait;

/**
 * Resolve Api based on the method name.
 *
 * @method Index index(array $options = [])  
 * @method Lists lists(array $options = [])  
 * @method Asc asc(array $options = [])  
 * @method Submit submit(array $options = [])  
 * @method Revoke revoke(array $options = [])  
 * @method Reply reply(array $options = [])  
 * @method Delreply delreply(array $options = [])  

 */
class ApiResolver
{
    use ApiResolverTrait;
}
