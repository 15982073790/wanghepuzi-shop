<?php

namespace MrstockCloud\News\Inneruse\V\Article;

use MrstockCloud\Client\Traits\ApiResolverTrait;

/**
 * Resolve Api based on the method name.
 *
 * @method Articlelist articlelist(array $options = []) whatFor="根据ID查询资讯列表",menuName="",codeMonkey=""
 * @method Add add(array $options = []) whatFor="新增股讯",menuName="",codeMonkey=""
 * @method Taglist taglist(array $options = []) whatFor="栏目列表(巨灵数据库用 
 * @method Getzhishulist getzhishulist(array $options = []) whatFor="获得指数新闻",menuName="",codeMonkey=""
 * @method Getnum getnum(array $options = []) whatFor="根据巨灵数据ID查询是否存在记录",menuName="",codeMonkey=""

 */
class ApiResolver
{
    use ApiResolverTrait;
}
