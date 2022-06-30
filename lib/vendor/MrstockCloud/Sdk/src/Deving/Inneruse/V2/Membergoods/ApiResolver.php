<?php

namespace MrstockCloud\Deving\Inneruse\V2\Membergoods;

use MrstockCloud\Client\Traits\ApiResolverTrait;

/**
 * Resolve Api based on the method name.
 *
 * @method Create create(array $options = []) whatFor="新增商品鉴权（支付回调用）",menuName="",codeMonkey=""
 * @method Customeropenservice customeropenservice(array $options = []) whatFor="客户管理开通服务",menuName="",codeMonkey=""
 * @method Closeservice closeservice(array $options = []) whatFor="客户管理关闭服务期",menuName="",codeMonkey=""
 * @method Member_goods_list member_goods_list(array $options = []) whatFor="开通的服务列表",menuName="",codeMonkey=""
 * @method Getmembergoodsisservice getmembergoodsisservice(array $options = []) whatFor="用户正在服务中的服务列表",menuName="",codeMonkey=""
 * @method Goods_user_list goods_user_list(array $options = []) whatFor="开通某个服务的成员列表",menuName="",codeMonkey=""
 * @method Admin_create admin_create(array $options = []) whatFor="新增商品鉴权(后台用 
 * @method Get_member_goods_time get_member_goods_time(array $options = []) whatFor="获得商品服务时间",menuName="",codeMonkey=""
 * @method Get_member_goods get_member_goods(array $options = []) whatFor="商品鉴权",menuName="",codeMonkey=""
 * @method Get_member_expire get_member_expire(array $options = []) whatFor="获取用户服务到期时间",menuName="",codeMonkey=""
 * @method Get_service_time get_service_time(array $options = []) whatFor="crm获取线上产品的服务开始时间和服务结束时间",menuName="",codeMonkey=""
 * @method Checkbuy checkbuy(array $options = []) whatFor="查询用户购买情况",menuName="",codeMonkey=""
 * @method Edit_end_time edit_end_time(array $options = []) whatFor="修改到期时间",menuName="",codeMonkey=""
 * @method Modify_service modify_service(array $options = []) whatFor="减去到期时间",menuName="",codeMonkey=""
 * @method Getmemberidbytime getmemberidbytime(array $options = []) whatFor="根据产品和月份查询正在服务期内的用户ID",menuName="",codeMonkey=""
 * @method Getmemberisbuy getmemberisbuy(array $options = []) whatFor="获取用户商品购买情况",menuName="",codeMonkey=""
 * @method Member_has_products member_has_products(array $options = []) whatFor="获取用户正在服务期中的产品ID",menuName="",codeMonkey=""
 * @method Getmemberservice getmemberservice(array $options = []) whatFor="获取用户在某个团队和分组下服务开始时间和结束时间",menuName="",codeMonkey=""

 */
class ApiResolver
{
    use ApiResolverTrait;
}
