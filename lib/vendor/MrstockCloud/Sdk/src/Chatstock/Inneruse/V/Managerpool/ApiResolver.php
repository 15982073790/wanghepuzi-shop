<?php

namespace MrstockCloud\Chatstock\Inneruse\V\Managerpool;

use MrstockCloud\Client\Traits\ApiResolverTrait;

/**
 * Resolve Api based on the method name.
 *
 * @method Checkprivilegecode checkprivilegecode(array $options = []) whatFor="判断邀请码存不存在",codeMonkey="申长春" 
 * @method Getprivilegecode getprivilegecode(array $options = []) whatFor="随机获取邀请码",codeMonkey="申长春" 
 * @method Getadminservicetype getadminservicetype(array $options = [])  
 * @method Getotheradminid getotheradminid(array $options = [])  

 */
class ApiResolver
{
    use ApiResolverTrait;
}
