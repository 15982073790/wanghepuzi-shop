<?php

namespace Common\Middleware;


use MrStock\System\Helper\Output;
use MrStock\System\MJC\Validate;

class Tel
{
    public function handle($request, \Closure $next)
    {
        $validate = new Validate();
        $validate->validateparam = [
            [
                'input' => $request['tel'],
                'require' => 'true',
                'message' => '手机号必须'
            ],
            [
                'input' => $request['tel'],
                'validator' => 'mobile',
                'message' => '手机号不正确'
            ]
        ];


        $error = $validate->validate();
        
        if ($error != '') {
            return Output::response($error, - 1);
        }
        return $next($request);
    }
}