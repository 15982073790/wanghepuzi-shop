<?php

namespace MrstockCloud\Membergoods\Inneruse\V2\Membergoods;

use MrstockCloud\Client\Traits\ApiResolverTrait;

/**
 * Resolve Api based on the method name.
 *
 * @method Closeservice closeservice(array $options = []) whatFor="客户管理关闭服务期",menuName="",codeMonkey="" 
 * @method Modify_service modify_service(array $options = []) whatFor="减去到期时间",menuName="",codeMonkey="" 
 * @method Member_goods_list member_goods_list(array $options = []) whatFor="开通的服务列表",menuName="",codeMonkey="" 
 * @method Getmembergoodsisservice getmembergoodsisservice(array $options = []) whatFor="用户正在服务中的服务列表",menuName="",codeMonkey="" 
 * @method Goods_user_list goods_user_list(array $options = []) whatFor="服务期中-在某个产品（团队，分组）下的成员列表",menuName="",codeMonkey="" 
 * @method Get_member_goods_time get_member_goods_time(array $options = []) whatFor="获得商品服务时间",menuName="",codeMonkey="" 
 * @method Get_member_goods get_member_goods(array $options = []) whatFor="商品鉴权",menuName="",codeMonkey="" 
 * @method Get_member_expire get_member_expire(array $options = []) whatFor="获取用户服务到期时间",menuName="",codeMonkey="" 
 * @method Get_service_time get_service_time(array $options = []) whatFor="crm获取线上产品的服务开始时间和服务结束时间",menuName="",codeMonkey="" 
 * @method Checkbuy checkbuy(array $options = []) whatFor="查询用户购买情况",menuName="",codeMonkey="" 
 * @method Getmemberidbytime getmemberidbytime(array $options = []) whatFor="根据产品和月份查询正在服务期内的用户ID",menuName="",codeMonkey="" 
 * @method Getmemberisbuy getmemberisbuy(array $options = []) whatFor="获取用户商品购买情况",menuName="",codeMonkey="" 
 * @method Member_has_products member_has_products(array $options = []) whatFor="获取用户正在服务期中的产品ID",menuName="",codeMonkey="" 
 * @method Getmemberservice getmemberservice(array $options = []) whatFor="获取用户在某个团队和分组下服务开始时间和结束时间",menuName="",codeMonkey="" 
 * @method Getmemberisservice getmemberisservice(array $options = []) whatFor="判断用户是再某个产品下某个团队中是否在服务期",menuName="",codeMonkey="" 
 * @method Getmemberidbymonthtime getmemberidbymonthtime(array $options = []) whatFor="根据（用户）者月份筛选出正在服务期中的用户",menuName="",codeMonkey="" 
 * @method Customergetservicelist customergetservicelist(array $options = []) whatFor="客户详情获取用户服务记录",menuName="",codeMonkey="" 
 * @method Membergoodsalllist membergoodsalllist(array $options = []) whatFor="所有服务记录",menuName="",codeMonkey="" 
 * @method Memberisservicebyid memberisservicebyid(array $options = []) whatFor="判断用户是否在服务期中",menuName="",codeMonkey="" 
 * @method Getmembergoodsteam getmembergoodsteam(array $options = []) whatFor="获取用户在服务期中的产品ID 和团队ID",menuName="",codeMonkey="" 
 * @method Getforbidopen getforbidopen(array $options = []) whatFor="获取用户剩余禁开期",menuName="",codeMonkey="" 

 */
class ApiResolver
{
    use ApiResolverTrait;
}
