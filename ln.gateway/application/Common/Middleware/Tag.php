<?php
namespace Common\Middleware;
use MrStock\System\Helper\Output;
use MrStock\System\MJC\Validate;

class Tag
{
    public function handle($request, \Closure $next)
    {
        $validate = new Validate();
        $validate->validateparam = [
           [
                'input' => intval($request['tag']),
                'require' => 'true',
                'message' => '请选择相关标签'
            ]
        ];
        $error = $validate->validate();
        if ($error != '') {
            return Output::response($error, - 1);
        }

        return $next($request);
    }
}