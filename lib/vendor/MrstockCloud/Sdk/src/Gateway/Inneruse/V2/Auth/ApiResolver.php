<?php

namespace MrstockCloud\Gateway\Inneruse\V2\Auth;

use MrstockCloud\Client\Traits\ApiResolverTrait;

/**
 * Resolve Api based on the method name.
 *
 * @method Checkappcodeapi checkappcodeapi(array $options = []) whatFor="鉴权appcode与apicode",menuName="",codeMonkey="王云" 
 * @method Adminislogin adminislogin(array $options = []) whatFor="鉴权admin与apicode",menuName="",codeMonkey="" 
 * @method Checkadminapi checkadminapi(array $options = []) whatFor="鉴权admin与apicode",menuName="",codeMonkey="" 
 * @method Adminhaverole adminhaverole(array $options = [])  

 */
class ApiResolver
{
    use ApiResolverTrait;
}
