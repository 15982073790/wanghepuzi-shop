<?php

namespace MrstockCloud\Goods\Inneruse\V\Goods;

use MrstockCloud\Client\Traits\ApiResolverTrait;

/**
 * Resolve Api based on the method name.
 *
 * @method Validate validate(array $options = []) whatFor="用于支付验证商品真实性",menuName="",codeMonkey=""
 * @method Pinfo pinfo(array $options = []) whatFor="获取产品的商品信息",menuName="",codeMonkey=""
 * @method Info info(array $options = []) whatFor="客户端获取商品支付信息",menuName="",codeMonkey=""
 * @method Create create(array $options = []) whatFor="新增/修改商品[后台新增商品信息的接口]",menuName="",codeMonkey=""
 * @method Getgoodsskuinfo getgoodsskuinfo(array $options = []) whatFor="获取商品价格信息",menuName="",codeMonkey=""
 * @method Getgoodslist getgoodslist(array $options = []) whatFor="获得可参加活动商品列表",menuName="",codeMonkey=""
 * @method Getcycle getcycle(array $options = []) whatFor="获取所有商品",menuName="",codeMonkey=""
 * @method Getactivitybysku getactivitybysku(array $options = []) whatFor="根据SKU获取商品活动",menuName="",codeMonkey=""
 * @method Activityopenjudge activityopenjudge(array $options = []) whatFor="活动上线判断",menuName="",codeMonkey=""

 */
class ApiResolver
{
    use ApiResolverTrait;
}
