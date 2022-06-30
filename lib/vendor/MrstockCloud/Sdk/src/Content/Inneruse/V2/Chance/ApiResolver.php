<?php

namespace MrstockCloud\Content\Inneruse\V2\Chance;

use MrstockCloud\Client\Traits\ApiResolverTrait;

/**
 * Resolve Api based on the method name.
 *
 * @method Setattention setattention(array $options = []) whatFor="设置跟踪走势",menuName="机会跟踪走势设置",codeMonkey="" 
 * @method Cancelattention cancelattention(array $options = []) whatFor="取消跟踪走势",menuName="机会跟踪走势设置",codeMonkey="" 
 * @method Sendchance sendchance(array $options = []) whatFor="新增发票",menuName="",codeMonkey="张庆" 

 */
class ApiResolver
{
    use ApiResolverTrait;
}
