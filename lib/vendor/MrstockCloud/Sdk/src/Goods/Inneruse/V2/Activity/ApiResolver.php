<?php

namespace MrstockCloud\Goods\Inneruse\V2\Activity;

use MrstockCloud\Client\Traits\ApiResolverTrait;

/**
 * Resolve Api based on the method name.
 *
 * @method Addactivity addactivity(array $options = []) whatFor="添加商品SKU活动信息",menuName="",codeMonkey="" 
 * @method Closeactivity closeactivity(array $options = []) whatFor="活动关闭或者结束",menuName="",codeMonkey="" 

 */
class ApiResolver
{
    use ApiResolverTrait;
}
