<?php

namespace MrstockCloud\Institution\Inneruse\V\Institution;

use MrstockCloud\Client\Traits\ApiResolverTrait;

/**
 * Resolve Api based on the method name.
 *
 * @method Getinfoinstitution getinfoinstitution(array $options = []) whatFor="查询机构详情",menuName="查询机构详情",codeMonkey="wangsongqing" 
 * @method Getinfocompany getinfocompany(array $options = []) whatFor="查询分公司详情",menuName="查询分公司详情",codeMonkey="wangsongqing" 
 * @method Getallcompany getallcompany(array $options = []) whatFor="获取所有公司信息",menuName="获取所有公司信息",codeMonkey="wangsongqing" 
 * @method Getalllist getalllist(array $options = []) whatFor="根据多个部门获取多个名称",menuName="根据多个部门获取多个名称",codeMonkey="wangsongqing" 

 */
class ApiResolver
{
    use ApiResolverTrait;
}
