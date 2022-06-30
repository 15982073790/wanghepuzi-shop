<?php
namespace Common\Middleware;
use MrStock\System\Helper\Output;
use MrStock\System\MJC\Validate;

class Department
{
    public function handle($request, \Closure $next)
    {
        $validate = new Validate();
        $validate->validateparam = [
           [
                'input' => intval($request['department_id']),
                'require' => 'true',
                'message' => '请选择所属部门'
            ]
        ];
        $error = $validate->validate();
        if ($error != '') {
            return Output::response($error, - 1);
        }

        return $next($request);
    }
}