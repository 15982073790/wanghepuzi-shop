<?php
namespace Common\Middleware;
use MrStock\System\Helper\Output;
use MrStock\System\MJC\Validate;

class Apicode
{
    public function handle($request, \Closure $next)
    {
        $validate = new Validate();
        $validate->validateparam = [
           [
                'input' => $request['apicode'],
                'require' => 'true',
                'message' => '接口标识不能为空'
            ]
        ];
        $error = $validate->validate();
        if ($error != '') {
            return Output::response($error, - 1);
        }

        return $next($request);
    }
}