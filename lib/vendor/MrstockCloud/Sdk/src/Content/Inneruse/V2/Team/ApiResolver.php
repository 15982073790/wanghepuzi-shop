<?php

namespace MrstockCloud\Content\Inneruse\V2\Team;

use MrstockCloud\Client\Traits\ApiResolverTrait;

/**
 * Resolve Api based on the method name.
 *
 * @method Joingroup joingroup(array $options = [])  
 * @method Hasjointeam hasjointeam(array $options = []) whatFor="查询用户已加入组",menuName="服务团队",codeMonkey=""
 * @method Latestjointeam latestjointeam(array $options = [])  
 * @method Getgrouplist getgrouplist(array $options = [])  
 * @method Getmembergroup getmembergroup(array $options = [])  

 */
class ApiResolver
{
    use ApiResolverTrait;
}
