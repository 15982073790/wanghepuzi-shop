<?php

namespace MrstockCloud\Institution\Inneruse\V\Groupframwork;

use MrstockCloud\Client\Traits\ApiResolverTrait;

/**
 * Resolve Api based on the method name.
 *
 * @method Getalldepartment getalldepartment(array $options = []) whatFor="获取机构下面所有的部门",menuName="组织架构管理",codeMonkey="" 
 * @method Getcompanyinfo getcompanyinfo(array $options = []) whatFor="根据id获取分公司或者部门信息",menuName="组织架构管理",codeMonkey="" 
 * @method Getdepartmentlist getdepartmentlist(array $options = []) whatFor="获取分公司信息crm调用",menuName="获取分公司信息crm调用",codeMonkey="" 
 * @method Getemployeelist getemployeelist(array $options = []) whatFor="根据部门id查询员工列表",menuName="组织架构管理",codeMonkey="" 
 * @method Getcompanylist getcompanylist(array $options = []) whatFor="根据机构ID获取机构下公司列表",menuName="组织架构管理",codeMonkey="" 
 * @method Getframworkcompany getframworkcompany(array $options = []) whatFor="根据分公司id 读取组织架构",menuName="组织架构管理",codeMonkey="" 
 * @method Getallchild getallchild(array $options = [])  
 * @method Delleaders delleaders(array $options = []) whatFor="取消某个员工某个部门的负责人身份",codeMonkey="scc" 

 */
class ApiResolver
{
    use ApiResolverTrait;
}
