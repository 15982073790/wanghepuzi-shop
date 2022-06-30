<?php

namespace MrstockCloud\Im\Inneruse\V\Taguser;

use MrstockCloud\Client\Traits\ApiResolverTrait;

/**
 * Resolve Api based on the method name.
 *
 * @method Lists lists(array $options = []) whatFor="标签用户列表",menuName="标签",codeMonkey="" 
 * @method Add add(array $options = []) whatFor="新增标签用户",menuName="标签",codeMonkey="" 
 * @method Del del(array $options = []) whatFor="删除标签用户",menuName="标签",codeMonkey="" 

 */
class ApiResolver
{
    use ApiResolverTrait;
}
