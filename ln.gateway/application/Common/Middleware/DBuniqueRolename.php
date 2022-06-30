<?php
namespace Common\Middleware;
use MrStock\System\Helper\Output;
use MrStock\System\MJC\Validate;
use MrStock\System\Orm\Model;
class DBuniqueRolename
{
    public function handle($request, \Closure $next)
    {
      
         $model=new Model("role");
         $role_id=$request["role_id"];
         $company_id=$request["company_id"];
         $where=[];
         if($company_id){
            $where["company_id"]=$company_id;
         }
         if(!empty($role_id)){
            $where["role_name"]=$request["role_name"];
            $where["role_id"]=["neq",$request["role_id"]];
         	$res=$model->where($where)->find();
         }else{
            $where["role_name"]=$request["role_name"];
         	$res=$model->where($where)->find();	
         }
         
         if(!empty($res)){
               return Output::response("已存在该角色名称", - 1); 
         }
        return $next($request);
    }
}