<?php

namespace MrstockCloud\Pay\Inneruse\V\Order;

use MrstockCloud\Client\Traits\ApiResolverTrait;

/**
 * Resolve Api based on the method name.
 *
 * @method Cloudorderexport cloudorderexport(array $options = []) whatFor="云平台导出订单",menuName="",codeMonkey="" 
 * @method Institutionorderexport institutionorderexport(array $options = []) whatFor="机构导出订单",menuName="",codeMonkey="" 
 * @method Companyorderexport companyorderexport(array $options = []) whatFor="公司导出订单",menuName="",codeMonkey="" 
 * @method Notservice notservice(array $options = []) whatFor="支付成功没加服务的",menuName="",codeMonkey="" 

 */
class ApiResolver
{
    use ApiResolverTrait;
}
