<?php

namespace MrstockCloud\Compliance\Inneruse\V2\Contract;

use MrstockCloud\Client\Traits\ApiResolverTrait;

/**
 * Resolve Api based on the method name.
 *
 * @method Get_status_by_order_ids get_status_by_order_ids(array $options = []) whatFor="根据订单ID 获取合同状态",menuName="",codeMonkey="杜阳" 
 * @method Check_wait_sign check_wait_sign(array $options = []) whatFor="判断是否有待签订合同",menuName="",codeMonkey="杜阳" 
 * @method Contractriskinfo contractriskinfo(array $options = []) whatFor="获取合同风测信息",menuName="合同风测信息",codeMonkey="" 

 */
class ApiResolver
{
    use ApiResolverTrait;
}
