<?php

namespace MrstockCloud\Content\Inneruse\V\Team;

use MrstockCloud\Client\Traits\ApiResolverTrait;

/**
 * Resolve Api based on the method name.
 *
 * @method Jointeam jointeam(array $options = []) whatFor="根据产品ID查询团推ID",menuName="服务团队",codeMonkey="" 
 * @method Hasjointeam hasjointeam(array $options = []) whatFor="查询用户已加入组",menuName="服务团队",codeMonkey="" 

 */
class ApiResolver
{
    use ApiResolverTrait;
}
