<?php

namespace Common\Middleware;


use MrStock\System\Helper\Output;
use MrStock\System\MJC\Validate;
use Common\Helper\FunctionHelper;
class Targetadmin
{
    public function handle($request, \Closure $next)
    {

        $validate = new Validate();
        $validate->validateparam = [
             [
                'input' => intval($request['targetadmin_id']),
                'require' => 'true',
                'message' => '目标用户不能为空'
            ]
        ];
        $error = $validate->validate();
        if ($error != '') {
            return Output::response($error, - 1);
        }
        //操作目标admin_id的权限验证
         // $model=new Model("admin");
         // $res=$model->where(["admin_id"=>$request["admin_id"]])->find();
         // if(empty($res)){
         //       return Output::response("账号异常", - 1); 
         // }
         // $admin_adminType=FunctionHelper::getAdminType($res);

         // $res=$model->where(["admin_id"=>$request["targetadmin_id"]])->find();
         // if(empty($res)){
         //       return Output::response("账号异常", - 1); 
         // }
         // $targetadmin_adminType=FunctionHelper::getAdminType($res);
         // if($admin_adminType=="cloud"&&$targetadmin_adminType=="institution"){
         //        return $next($request);
         // }
         // if($admin_adminType=="institution"&&$targetadmin_adminType=="company"){
         //        return $next($request);
         // }
         // if($admin_adminType=="company"&&$targetadmin_adminType=="team"){
         //        return $next($request);
         // }
         // return Output::response("无权操作该targetadmin_id", - 1); 

         //end
        return $next($request);
    }
}