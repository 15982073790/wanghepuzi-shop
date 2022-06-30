<?php

namespace MrstockCloud\Traders\Inneruse\V2\Live;

use MrstockCloud\Client\Traits\ApiResolverTrait;

/**
 * Resolve Api based on the method name.
 *
 * @method Getliveinfobyid getliveinfobyid(array $options = []) whatFor="根据直播间ID查询信息",menuName="",codeMonkey="" 
 * @method Disableteacher disableteacher(array $options = []) whatFor="账号管理取消老师标签",menuName="",codeMonkey="" 
 * @method Closeteacher closeteacher(array $options = []) whatFor="账号管理禁用老师-关闭直播",menuName="",codeMonkey="" 
 * @method Disabassistant disabassistant(array $options = []) whatFor="账号管理取消助理标签",menuName="",codeMonkey="" 

 */
class ApiResolver
{
    use ApiResolverTrait;
}
