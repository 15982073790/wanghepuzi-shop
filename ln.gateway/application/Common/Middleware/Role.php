<?php
namespace Common\Middleware;
use MrStock\System\Helper\Output;
use MrStock\System\MJC\Validate;

class role
{
    public function handle($request, \Closure $next)
    {
        $validate = new Validate();
        $validate->validateparam = [
           [
                'input' => intval($request['role_id']),
                'require' => 'true',
                'message' => '角色id不能为空！'
            ]
        ];
        $error = $validate->validate();
        if ($error != '') {
            return Output::response($error, - 1);
        }

        return $next($request);
    }
}