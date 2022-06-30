<?php

namespace MrstockCloud\Pay\Inneruse\V2\Order;

use MrstockCloud\Client\Traits\ApiResolverTrait;

/**
 * Resolve Api based on the method name.
 *
 * @method Cloudorderexport cloudorderexport(array $options = []) whatFor="云平台导出订单",menuName="",codeMonkey="" 
 * @method Institutionorderexport institutionorderexport(array $options = []) whatFor="机构导出订单",menuName="",codeMonkey="" 
 * @method Companyorderexport companyorderexport(array $options = []) whatFor="公司导出订单",menuName="",codeMonkey="" 
 * @method Notservice notservice(array $options = []) whatFor="支付成功没加服务的",menuName="",codeMonkey="" 
 * @method Getorderinfo getorderinfo(array $options = []) whatFor="查询用户线下支付订单",menuName="",codeMonkey="" 
 * @method Getmemberorder getmemberorder(array $options = []) whatFor="获取用户订单 或者全部订单",menuName="",codeMonkey="" 
 * @method Getmemberorderisservice getmemberorderisservice(array $options = []) whatFor="判断用户是否存在购买了 未开通服务的的情况",menuName="",codeMonkey="" 
 * @method Getorderinfobyid getorderinfobyid(array $options = []) whatFor="根据ID集合获取订单信息",menuName="",codeMonkey="" 
 * @method Getorderinfoisservice getorderinfoisservice(array $options = [])  
 * @method Cancel cancel(array $options = []) whatFor="注销前置判断",menuName="",codeMonkey="童彦铭" 

 */
class ApiResolver
{
    use ApiResolverTrait;
}
