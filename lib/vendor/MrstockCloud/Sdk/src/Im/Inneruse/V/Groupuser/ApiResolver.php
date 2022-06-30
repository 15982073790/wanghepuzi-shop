<?php

namespace MrstockCloud\Im\Inneruse\V\Groupuser;

use MrstockCloud\Client\Traits\ApiResolverTrait;

/**
 * Resolve Api based on the method name.
 *
 * @method Add add(array $options = []) whatFor="新增群成员",menuName="群成员相关",codeMonkey="" 
 * @method Del del(array $options = []) whatFor="删除群成员",menuName="群成员相关",codeMonkey="" 
 * @method Lists lists(array $options = []) whatFor="获取群成员",menuName="群成员相关",codeMonkey="" 

 */
class ApiResolver
{
    use ApiResolverTrait;
}
