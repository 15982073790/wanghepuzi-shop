<?php

namespace Common\Middleware;


use MrStock\System\Helper\Output;
use MrStock\System\MJC\Validate;

class ManagerOrderUpdateorderstatus
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
                'require' => true,
                'validator' => 'custom',
                'regexp'    => '/^4$/',
                'message'   => 'order_status必须为4'
            ]
        ];


        $error = $validate->validate();

        if ($error != '') {
            return Output::response($error, -1);
        }
        return $next($request);
    }
}