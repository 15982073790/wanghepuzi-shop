<?php

namespace MrstockCloud\Stock\Inneruse\V2\Stock;

use MrstockCloud\Client\Traits\ApiResolverTrait;

/**
 * Resolve Api based on the method name.
 *
 * @method Stockone stockone(array $options = []) whatFor="查询一条标的",menuName="发布机会",codeMonkey="",versions=1.1 
 * @method Stockpublic stockpublic(array $options = []) whatFor="发布（立即发布,定时发布）",menuName="发布机会,codeMonkey="",versions=1.1 
 * @method TimerPublic timerpublic(array $options = []) whatFor="定时器发布",menuName="发布机会,codeMonkey=""，versions=1.1 
 * @method Searchstock searchstock(array $options = []) whatFor="搜索标的id列表",menuName="查看",codeMonkey="申长春" 
 * @method Stockcancel stockcancel(array $options = []) whatFor="取消发布",menuName="发布机会,codeMonkey=""，versions=1.1 
 * @method Stockonefield stockonefield(array $options = []) whatFor="根据stock_id查询想要的字段",menuName="发布机会",codeMonkey="",versions=1.1 

 */
class ApiResolver
{
    use ApiResolverTrait;
}
