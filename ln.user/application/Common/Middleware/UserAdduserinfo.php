<?php

namespace Common\Middleware;


use MrStock\System\Helper\Output;
use MrStock\System\MJC\Validate;

class UserAdduserinfo
{
    public function handle($request, \Closure $next)
    {
        $validate = new Validate();
        $validate->validateparam = [
            [
                'input' => $request['wx_openid'],
                'require' => true,
                'message' => '微信头像必须'
            ],
            [
                'input' => $request['wx_name'],
                'require' => true,
                'message' => '微信名必须'
            ],
            [
                'input' => $request['wx_avatar'],
                'require' => true,
                'message' => 'wx_avatar必须'
            ]
        ];


        $error = $validate->validate();
        
        if ($error != '') {
            return Output::response($error, - 1);
        }
        return $next($request);
    }
}