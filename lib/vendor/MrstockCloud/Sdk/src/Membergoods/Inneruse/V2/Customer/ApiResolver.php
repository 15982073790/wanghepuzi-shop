<?php

namespace MrstockCloud\Membergoods\Inneruse\V2\Customer;

use MrstockCloud\Client\Traits\ApiResolverTrait;

/**
 * Resolve Api based on the method name.
 *
 * @method Getlist getlist(array $options = []) whatFor="通过用户ID/产品ID/公司ID/服务开始时间区间/服务结束时间区间/剩余服务期查询在服务期中的用户服务信息",menuName="",codeMonkey="" 
 * @method Getaddservicelog getaddservicelog(array $options = []) whatFor="客户管理获取批量加服务期失败数据",menuName="",codeMonkey="" 

 */
class ApiResolver
{
    use ApiResolverTrait;
}
