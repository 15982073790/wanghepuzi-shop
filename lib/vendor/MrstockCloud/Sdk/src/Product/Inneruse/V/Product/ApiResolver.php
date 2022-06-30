<?php

namespace MrstockCloud\Product\Inneruse\V\Product;

use MrstockCloud\Client\Traits\ApiResolverTrait;

/**
 * Resolve Api based on the method name.
 *
 * @method Getlist getlist(array $options = [])  
 * @method Packaging packaging(array $options = []) whatFor="新增用户产品列表",codeMonkey=""
 * @method Getcompliance getcompliance(array $options = [])  
 * @method Getalllist getalllist(array $options = [])  
 * @method Getteaminfo getteaminfo(array $options = [])  
 * @method Info info(array $options = [])  
 * @method Getproductlist getproductlist(array $options = [])  
 * @method Getprice getprice(array $options = [])  
 * @method Getparent getparent(array $options = [])  
 * @method Getlevelproduct getlevelproduct(array $options = [])  
 * @method Getinfo getinfo(array $options = [])  
 * @method Getproductlevel getproductlevel(array $options = [])  

 */
class ApiResolver
{
    use ApiResolverTrait;
}
