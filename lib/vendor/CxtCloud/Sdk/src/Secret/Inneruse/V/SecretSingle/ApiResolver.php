<?php

namespace CxtCloud\Secret\Inneruse\V\SecretSingle;

use MrstockCloud\Client\Traits\ApiResolverTrait;

/**
 * Resolve Api based on the method name.
 *
 * @method Encrypt encrypt(array $options = [])  
 * @method Decrypt decrypt(array $options = [])  

 */
class ApiResolver
{
    use ApiResolverTrait;
}
