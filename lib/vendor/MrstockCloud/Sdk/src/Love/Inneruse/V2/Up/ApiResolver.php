<?php

namespace MrstockCloud\Love\Inneruse\V2\Up;

use MrstockCloud\Client\Traits\ApiResolverTrait;

/**
 * Resolve Api based on the method name.
 *
 * @method Getuplist getuplist(array $options = []) whatFor="获取点赞列表",menuName="",codeMonkey="ty" 
 * @method Getupnum getupnum(array $options = []) whatFor="获取点赞数",menuName="",codeMonkey="ty" 
 * @method Addup addup(array $options = []) whatFor="添加点赞",menuName="",codeMonkey="ty" 
 * @method Isup isup(array $options = []) whatFor="判断是否点赞",menuName="",codeMonkey="ty" 
 * @method Delup delup(array $options = [])  

 */
class ApiResolver
{
    use ApiResolverTrait;
}
