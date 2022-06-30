<?php

namespace MrstockCloud\Pay\Inneruse\V\Redbag;

use MrstockCloud\Client\Traits\ApiResolverTrait;

/**
 * Resolve Api based on the method name.
 *
 * @method Exportchat exportchat(array $options = []) whatFor="导出红包记录(聊股 
 * @method Exportsolution exportsolution(array $options = []) whatFor="导出红包记录(解盘 

 */
class ApiResolver
{
    use ApiResolverTrait;
}
