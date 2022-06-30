<?php

namespace MrstockCloud\Sysconfig\Inneruse\V\TextSupport;

use MrstockCloud\Client\Traits\ApiResolverTrait;

/**
 * Resolve Api based on the method name.
 *
 * @method Info info(array $options = []) whatFor="文本单个详情,agree_mark是可选参数.id优先.",menuName="详情",codeMonkey="" 
 * @method Infonocontent infonocontent(array $options = []) whatFor="文本单个详情,不反悔content字段,agree_mark是可选参数.id优先.",menuName="详情",codeMonkey="scc" 

 */
class ApiResolver
{
    use ApiResolverTrait;
}
