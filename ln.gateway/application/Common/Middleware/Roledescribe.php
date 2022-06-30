<?php
namespace Common\Middleware;
use MrStock\System\Helper\Output;
use MrStock\System\MJC\Validate;

class Roledescribe
{
    public function handle($request, \Closure $next)
    {
        $validate = new Validate();
        $validate->validateparam = [
           [
                'input' => $request['role_describe'],
                'require' => 'true',
                'message' => '角色描述不能为空！'
            ],
            [
                'input' => $request['role_describe'],
                'validator' => 'Length',
                'max' => '50',
                'message' => '角色描述超过长度！'
            ]
        ];
        $error = $validate->validate();
        if ($error != '') {
            return Output::response($error, - 1);
        }

        return $next($request);
    }
}