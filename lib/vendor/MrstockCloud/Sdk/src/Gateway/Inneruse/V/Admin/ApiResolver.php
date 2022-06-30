<?php

namespace MrstockCloud\Gateway\Inneruse\V\Admin;

use MrstockCloud\Client\Traits\ApiResolverTrait;

/**
 * Resolve Api based on the method name.
 *
 * @method Selectall selectall(array $options = []) whatFor="crm同步后台账号数据",codeMonkey="" 
 * @method Selectbyadminids selectbyadminids(array $options = []) whatFor="根据后台账号id集合获取账号名字信息",codeMonkey="" 
 * @method Getadminidbyusername getadminidbyusername(array $options = []) whatFor="根据账号昵称查询账号ID",codeMonkey="" 
 * @method Getadminidbyzhiyebianhao getadminidbyzhiyebianhao(array $options = []) whatFor="根据用户执业编号查询账号ID",codeMonkey="" 
 * @method Getadminidsbyzhiyebianhao getadminidsbyzhiyebianhao(array $options = []) whatFor="根据用户执业编号查询多个账号ID",codeMonkey="" 
 * @method Getadminidbyprivilegecode getadminidbyprivilegecode(array $options = []) whatFor="根据账号邀请码查询账号ID",codeMonkey="" 
 * @method Getadminidbytruename getadminidbytruename(array $options = []) whatFor="根据账号真实姓名模糊搜索查询账号ID",codeMonkey="" 
 * @method Getadminidbytruenamelike getadminidbytruenamelike(array $options = []) whatFor="根据账号真实姓名模糊搜索查询账号ID",codeMonkey="" 
 * @method Selectlist selectlist(array $options = []) whatFor="账号管理",menuName="",codeMonkey="" 
 * @method Getadminbydepids getadminbydepids(array $options = []) whatFor=" 获取部门下的所有 admin_id",menuName="",codeMonkey="申长春" 
 * @method Getadminidsbyapicode getadminidsbyapicode(array $options = []) whatFor="根据多个接口标识获取拥有权限的用户id",menuName="",codeMonkey="" 
 * @method Databyadminid databyadminid(array $options = []) whatFor="根据后台账号id获取账号数据权限",codeMonkey="" 
 * @method Yw_admin_role yw_admin_role(array $options = []) whatFor="绑定业务权限或者解除业务权限",codeMonkey="" 
 * @method Checkprivilegecode checkprivilegecode(array $options = []) whatFor="判断邀请码存不存在",codeMonkey="" 
 * @method Getprivilegecode getprivilegecode(array $options = []) whatFor="随机获取邀请码",codeMonkey="" 
 * @method Innerusetest innerusetest(array $options = [])  
 * @method Get_dep_number_count get_dep_number_count(array $options = [])  

 */
class ApiResolver
{
    use ApiResolverTrait;
}
