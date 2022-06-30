<?php

namespace Common\Middleware;


use MrStock\System\Helper\Output;
use MrStock\System\MJC\Validate;

class Orderupdateorderstatus
{
    public function handle($request, \Closure $next)
    {
        $validate                = new Validate();
        $validate->validateparam = [
            [
                'input'   => $request['order_id'],
                'require' => true,
                'message' => 'order_id必填'
            ],
            [
                'input'     => $request['order_status'],
                'validator' => 'custom',
                'require' => true,
                'regexp'    => '/^(-1|-2|-3|5)?$/',
                'message'   => 'order_status必须为-1|-2|-3|5'
            ]
        ];


        $error = $validate->validate();

        if ($error != '') {
            return Output::response($error, -1);
        }
        return $next($request);
    }
}