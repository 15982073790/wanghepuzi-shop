<?php

namespace MrstockCloud\Compliance\Inneruse\V2\Member;

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
 * @method Tackcontract tackcontract(array $options = []) whatFor="生效合同",menuName="",codeMonkey="梁辉" 
 * @method Addsige addsige(array $options = []) whatFor="签名列表",menuName="确认签名",codeMonkey="梁辉" 
 * @method Rebet rebet(array $options = []) whatFor="签名列表",menuName="驳回签名",codeMonkey="梁辉" 
 * @method Getmembercompliances getmembercompliances(array $options = []) whatFor="个人合规信息",menuName="合规信息查看",codeMonkey="梁辉" 
 * @method Getclients getclients(array $options = []) whatFor="客户合规信息",menuName="客户查看详情",codeMonkey="梁辉" 
 * @method Getrusts getrusts(array $options = []) whatFor="风测问卷",menuName="查看问卷详情",codeMonkey="梁辉" 
 * @method Getusers getusers(array $options = []) whatFor="身份信息",menuName="获取用户真实信息",codeMonkey="梁辉" 
 * @method Getcom getcom(array $options = []) whatFor="个人合规信息",menuName="合规信息查看",codeMonkey="梁辉" 

 */
class ApiResolver
{
    use ApiResolverTrait;
}
