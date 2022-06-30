<?php

namespace MrstockCloud\Love\Inneruse\V\Up;

use MrstockCloud\Client\Traits\ApiResolverTrait;

/**
 * Resolve Api based on the method name.
 *
 * @method Getuplist getuplist(array $options = []) whatFor="获取点赞列表",menuName="",codeMonkey="ty" 
 * @method Getupnum getupnum(array $options = []) whatFor="获取点赞数",menuName="",codeMonkey="ty" 

 */
class ApiResolver
{
    use ApiResolverTrait;
}
