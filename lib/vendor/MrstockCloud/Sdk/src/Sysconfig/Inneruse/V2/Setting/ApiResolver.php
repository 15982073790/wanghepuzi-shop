<?php

namespace MrstockCloud\Sysconfig\Inneruse\V2\Setting;

use MrstockCloud\Client\Traits\ApiResolverTrait;

/**
 * Resolve Api based on the method name.
 *
 * @method Value value(array $options = []) whatFor="获取系统值",menuName="系统值",codeMonkey=""
 * @method Getxmssetting getxmssetting(array $options = []) whatFor="获取小秘书设置详情",menuName="小秘书",codeMonkey="申长春"
 * @method Getfeedbacksetting getfeedbacksetting(array $options = []) whatFor="获取建议投诉设置详情",menuName="小秘书",codeMonkey="申长春"
 * @method Setxmsandfeedback setxmsandfeedback(array $options = []) whatFor="设置小秘书,建议投诉",menuName="小秘书",codeMonkey="申长春"

 */
class ApiResolver
{
    use ApiResolverTrait;
}
