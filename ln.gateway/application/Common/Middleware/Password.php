<?php

namespace Common\Middleware;


use MrStock\System\Helper\Output;
use MrStock\System\MJC\Validate;
use Common\Model\AdminModel;
class Password
{
    public function handle($request, \Closure $next)
    {
         $model=new AdminModel();
         $res=$model->checkpassword($request["targetadmin_id"],$request["password"]);

         if(empty($res)){
                  
                $validate = new Validate();
                $validate->validateparam = [
                     [
                        'input' => $request['password'],
                        'require' => 'true',
                        'validator' => 'custom',
                        'regexp' => '/(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?!.*\s).{6,12}/',
                        'message' => '密码格式错误'
                    ]
                ];
                $error = $validate->validate();
                if ($error != '') {
                    return Output::response($error, - 1);
                }
        }
        return $next($request);
    }
}