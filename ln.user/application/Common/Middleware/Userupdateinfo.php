<?php

namespace Common\Middleware;


use MrStock\System\Helper\Output;
use MrStock\System\MJC\Validate;

class Userupdateinfo
{
    public function handle($request, \Closure $next)
    {
        $validate = new Validate();
        $validate->validateparam = [
            [
                'input' => $request['user_id'],
                'require' => true,
                'message' => 'user_id必填'
            ],
            [
                'input' => $request['user_name'],
                'require' => true,
                'validator' => 'chinese_name',
                'message' => '姓名格式不正确'
            ],
            [
                'input' => $request['tel'],
                'require' => true,
                'validator' => 'mobile',
                'message' => '手机号格式不正确'
            ],
            [
                'input' => $request['province_id'],
                'require' => true,
                'message' => 'province_id必须'
            ],
            [
                'input' => $request['city_id'],
                'require' => true,
                'message' => 'city_id必须'
            ],
            [
                'input' => $request['coupon_money'],
                'require' => true,
                'validator' => 'custom',
                'regexp' => '/^[0-9]+(.[0-9]{2}){1}$/',
                'message' => 'coupon_money格式不对'
            ],
            [
                'input' => $request['expire_time'],
                'validator' => 'custom',
                'regexp' => '/^[0-9]{10}$/',
                'message' => 'expire_time格式不对'
            ]
        ];


        $error = $validate->validate();
        
        if ($error != '') {
            return Output::response($error, - 1);
        }
        return $next($request);
    }
}