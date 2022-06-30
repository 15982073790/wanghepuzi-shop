<?php

namespace CxtCloud\Gateway\Inneruse\V\Admin;

use MrstockCloud\Client\Traits\ApiResolverTrait;

/**
 * Resolve Api based on the method name.
 *
 * @method Selectbyadminids selectbyadminids(array $options = []) whatFor="根据后台账号id集合获取账号名字信息",codeMonkey="" 
 * @method Getadminidbyusername getadminidbyusername(array $options = []) whatFor="根据账号昵称查询账号ID",codeMonkey="" 
 * @method Databyadminid databyadminid(array $options = []) whatFor="根据后台账号id获取账号数据权限",codeMonkey="" 

 */
class ApiResolver
{
    use ApiResolverTrait;
}
