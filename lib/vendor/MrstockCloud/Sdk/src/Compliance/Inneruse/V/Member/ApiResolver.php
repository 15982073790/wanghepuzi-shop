<?php

namespace MrstockCloud\Compliance\Inneruse\V\Member;

use MrstockCloud\Client\Traits\ApiResolverTrait;

/**
 * Resolve Api based on the method name.
 *
 * @method Membercompliance membercompliance(array $options = []) whatFor="获取客户合规状态",menuName="获取客户合规状态",codeMonkey="" 
 * @method Membercontractone membercontractone(array $options = []) whatFor="查询某个订单合同签订状态",menuName="查询某个订单合同签订状态",codeMonkey="" 
 * @method Updatecontractshow updatecontractshow(array $options = []) whatFor="修改合同显示状态",menuName="修改合同显示状态",codeMonkey="" 
 * @method Membergoodslevel membergoodslevel(array $options = []) whatFor="获取客户可以购买的商品等级",menuName="获取客户可以购买的商品等级",codeMonkey="" 
 * @method Createcontract createcontract(array $options = []) whatFor="生成合同",menuName="生成合同",codeMonkey="" 
 * @method Upservicetime upservicetime(array $options = []) whatFor="开通服务时修改合同的服务时间",menuName="开通服务时修改合同的服务时间",codeMonkey="" 
 * @method Memberrisklevel memberrisklevel(array $options = []) whatFor="获取客户在公司的风险等级",menuName="获取客户在公司的风险等级",codeMonkey="" 
 * @method Getsigndoclist getsigndoclist(array $options = []) whatFor="获取支付时需签署的文档列表",menuName="",codeMonkey="" 
 * @method Getsigndocone getsigndocone(array $options = []) whatFor="获取某个支付时需签署的文档内容",menuName="",codeMonkey="" 
 * @method Signcontract signcontract(array $options = []) whatFor="签订合同",menuName="",codeMonkey="" 
 * @method Invalidatecontract invalidatecontract(array $options = []) whatFor="失效合同",menuName="",codeMonkey="" 

 */
class ApiResolver
{
    use ApiResolverTrait;
}
