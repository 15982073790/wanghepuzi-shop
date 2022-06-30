<?php

namespace MrstockCloud\Compliance\Inneruse\V2\Memberstocks;

use MrstockCloud\Client\Traits\ApiResolverTrait;

/**
 * Resolve Api based on the method name.
 *
 * @method Riskinfo riskinfo(array $options = []) whatFor="获取诊股风测信息",menuName="获取诊股风测信息",codeMonkey="梁辉" 
 * @method Riskquestion riskquestion(array $options = []) whatFor="获取投股风测试题",menuName="获取投股风测试题",codeMonkey="梁辉" 
 * @method Precomputation precomputation(array $options = []) whatFor="预计算风测等级",menuName="预计算风测等级",codeMonkey="梁辉" 
 * @method Calculate calculate(array $options = []) whatFor="计算风测等级",menuName="计算风测等级",codeMonkey="梁辉" 

 */
class ApiResolver
{
    use ApiResolverTrait;
}
