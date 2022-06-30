<?php

namespace MrstockCloud\Im\Inneruse\V2\Mytest;

use MrstockCloud\Client\Traits\ApiResolverTrait;

/**
 * Resolve Api based on the method name.
 *
 * @method Test test(array $options = []) whatFor="测试",menuName="测试",codeMonkey="" 
 * @method Clearmsg clearmsg(array $options = []) whatFor="清空对话框消息",menuName="对话框相关",codeMonkey="" 

 */
class ApiResolver
{
    use ApiResolverTrait;
}
