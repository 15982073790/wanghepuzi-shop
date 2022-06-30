<?php

namespace MrstockCloud\Sysconfig\Inneruse\V\Setting;

use MrstockCloud\Client\Traits\ApiResolverTrait;

/**
 * Resolve Api based on the method name.
 *
 * @method Value value(array $options = []) whatFor="获取系统值",menuName="系统值",codeMonkey="" 
 * @method Setxms setxms(array $options = []) whatFor="设置小秘书默认欢迎语",menuName="小秘书",codeMonkey="申长春" 
 * @method Getxmstx getxmstx(array $options = []) whatFor="设置小秘书默认欢迎语",menuName="小秘书",codeMonkey="申长春" 
 * @method Getxmsname getxmsname(array $options = []) whatFor="设置小秘书默认欢迎语",menuName="小秘书",codeMonkey="申长春" 
 * @method Getxmskeydetail getxmskeydetail(array $options = []) whatFor="获取小秘书关键词详情",menuName="小秘书",codeMonkey="申长春" 

 */
class ApiResolver
{
    use ApiResolverTrait;
}
