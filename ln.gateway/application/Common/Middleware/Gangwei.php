<?php
namespace Common\Middleware;
use MrStock\System\Helper\Output;
use MrStock\System\MJC\Validate;

class Gangwei
{
    public function handle($request, \Closure $next)
    {
        $validate = new Validate();
        $validate->validateparam = [
           [
                'input' => $request['gangwei'],
                'require' => 'true',
                'message' => '请选择职业岗位'
            ]
        ];
        $error = $validate->validate();
        if ($error != '') {
            return Output::response($error, - 1);
        }

        return $next($request);
    }
}