<?php

namespace Common\Middleware;


use MrStock\System\Helper\Output;
use MrStock\System\MJC\Validate;

class Userstartstopdel
{
    public function handle($request, \Closure $next)
    {
        $validate = new Validate();
        $validate->validateparam = [
            [
                'input' => $request['datastatus'],
                'require' => true,
                'validator' => 'range',
                'min' => '1',
                'max' => '3',
                'message' => '必须1-3'
            ],
        ];
        $error = $validate->validate();
        
        if ($error != '') {
            return Output::response($error, - 1);
        }
        return $next($request);
    }
}