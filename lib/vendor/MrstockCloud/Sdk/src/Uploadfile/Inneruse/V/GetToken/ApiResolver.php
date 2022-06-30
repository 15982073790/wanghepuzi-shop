<?php

namespace MrstockCloud\Uploadfile\Inneruse\V\GetToken;

use MrstockCloud\Client\Traits\ApiResolverTrait;

/**
 * Resolve Api based on the method name.
 *
 * @method Getosstoken getosstoken(array $options = []) whatFor="获取oss的token",codeMonkey="申长春" 
 * @method Getheguiosstoken getheguiosstoken(array $options = []) whatFor="获取合规的oss的token",codeMonkey="王云" 
 * @method Getcxtosstoken getcxtosstoken(array $options = []) whatFor="获取财学堂的oss的token",codeMonkey="王云" 
 * @method Getossurl getossurl(array $options = []) whatFor="oss鉴权",codeMonkey="王云" 

 */
class ApiResolver
{
    use ApiResolverTrait;
}
