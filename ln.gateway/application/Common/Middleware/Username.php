<?php

namespace Common\Middleware;


use MrStock\System\Helper\Output;
use MrStock\System\MJC\Validate;
use MrStock\System\Orm\Model;
class username
{
    public function handle($request, \Closure $next)
    {
        $validate = new Validate();
        $validate->validateparam = [
            [
                'input' => $request['username'],
                'require' => 'true',
                'validator' => 'custom',
                'regexp' => '/^[a-zA-Z0-9]{6,12}$/',
                'message' => '登录账号格式不正确'
            ]
        ];
        $error = $validate->validate();
        if ($error != '') {
            return Output::response($error, - 1);
        }
        //整个系统不能重复
         $model=new Model("admin");
         $request["targetadmin_id"]=$request["targetadmin_id"]?:"";
         $res=$model->where(["username"=>$request["username"],"admin_id"=>["neq",$request["targetadmin_id"]]])->find();
         
         if(!empty($res)){
               return Output::response("用户名重复", - 1); 
         }

        return $next($request);
    }
}