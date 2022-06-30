<?php

namespace MrstockCloud\Message\Inneruse\V\Access;

use MrstockCloud\Client\Traits\ApiResolverTrait;

/**
 * Resolve Api based on the method name.
 *
 * @method Inqueue inqueue(array $options = []) whatFor="注册任务",menuName="",codeMonkey="" 
 * @method Notice1 notice1(array $options = []) whatFor="php内部接入文档-触发点预览",menuName="",codeMonkey="" 
 * @method Notice2 notice2(array $options = []) whatFor="php内部接入文档-传参约定",menuName="",codeMonkey="" 
 * @method Jumpsetting jumpsetting(array $options = []) whatFor="获取消息系统-跳转子服务公共配置",menuName="",codeMonkey="" 

 */
class ApiResolver
{
    use ApiResolverTrait;
}
