<?php

namespace MrstockCloud\Solution\Inneruse\V\Teacher;

use MrstockCloud\Client\Traits\ApiResolverTrait;

/**
 * Resolve Api based on the method name.
 *
 * @method Canceltag canceltag(array $options = []) whatFor="账号标签取消",menuName="",codeMonkey="" 
 * @method Forbiddenaccount forbiddenaccount(array $options = []) whatFor="老师账号禁用",menuName="",codeMonkey="" 
 * @method Searchteacher searchteacher(array $options = []) whatFor="搜索解盘老师列表",menuName="",codeMonkey="" 

 */
class ApiResolver
{
    use ApiResolverTrait;
}
