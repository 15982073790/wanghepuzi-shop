<?php

namespace MrstockCloud\Membergoods\Inneruse\V2\Membergoodsservice;

use MrstockCloud\Client\Traits\ApiResolverTrait;

/**
 * Resolve Api based on the method name.
 *
 * @method Getmembergoodsisservice getmembergoodsisservice(array $options = []) whatFor="根据产品（团队，用户ID）查询在服务期中用户",menuName="",codeMonkey="" 
 * @method Getmemberidsbyomtd getmemberidsbyomtd(array $options = []) whatFor="根据（产品，用户ID,开始时间，结束时间，剩余服务天数）查询在服务期中用户ID",menuName="",codeMonkey="" 
 * @method Getmemberlistbyid getmemberlistbyid(array $options = []) whatFor="根据用户ID查询在服务信息",menuName="",codeMonkey="" 

 */
class ApiResolver
{
    use ApiResolverTrait;
}
