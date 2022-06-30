<?php

namespace MrstockCloud\Pay\Inneruse\V\Crmapi;

use MrstockCloud\Client\Traits\ApiResolverTrait;

/**
 * Resolve Api based on the method name.
 *
 * @method Modify_order_visit modify_order_visit(array $options = []) whatFor="更新回访状态",menuName="",codeMonkey=""
 * @method Get_order_info get_order_info(array $options = []) whatFor="获取近期订单状态",menuName="",codeMonkey=""
 * @method Validate_order validate_order(array $options = []) whatFor="验证订单有效性",menuName="",codeMonkey=""
 * @method Remove_cooper remove_cooper(array $options = []) whatFor="删除合作情况作废订单",menuName="",codeMonkey=""
 * @method Order_success order_success(array $options = []) whatFor="crm获取最近支付成功的订单",menuName="",codeMonkey=""
 * @method Modify_payment modify_payment(array $options = []) whatFor="财务审核通过修改订单支付方式",menuName="",codeMonkey=""
 * @method Related_seller related_seller(array $options = []) whatFor="关联订单和业务员的关系",menuName="",codeMonkey=""
 * @method Modify_cooper modify_cooper(array $options = []) whatFor="关联crm合作情况id",menuName="",codeMonkey=""
 * @method Get_order_num get_order_num(array $options = []) whatFor="订单的基本信息",menuName="",codeMonkey=""

 */
class ApiResolver
{
    use ApiResolverTrait;
}
