<?php

namespace CxtCloud\Secret\Inneruse\V\Secretmobile;

use MrstockCloud\Client\Traits\ApiResolverTrait;

/**
 * Resolve Api based on the method name.
 *
 * @method Encrypt encrypt(array $options = []) whatFor="手机加密",codeMonkey=""
 * @method Decrypt decrypt(array $options = []) whatFor="手机解密",codeMonkey=""

 */
class ApiResolver
{
    use ApiResolverTrait;
}
