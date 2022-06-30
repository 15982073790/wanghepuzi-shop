<?php
namespace Common\Middleware;
use MrStock\System\Helper\Output;
use MrStock\System\MJC\Validate;
class Rolename
{
    public function handle($request, \Closure $next)
    {
        $validate = new Validate();
        $validate->validateparam = [
           [
                'input' => $request['role_name'],
                'require' => 'true',
                'message' => '角色名称不能为空！'
            ],
            [
                'input' => $request['role_name'],
                'validator' => 'Length',
                'max' => '20',
                'message' => '角色名称超过长度！'
            ]
        ];
        $error = $validate->validate();
        if ($error != '') {
            return Output::response($error, - 1);
        }
       
        return $next($request);
    }
}