<?php

namespace Common\Middleware;


use MrStock\System\Helper\Output;
use MrStock\System\MJC\Validate;

class Addressupdateaddress
{
    public function handle($request, \Closure $next)
    {
        $validate                = new Validate();
        $validate->validateparam = [
            [
                'input'   => $request['wx_openid'],
                'require' => true,
                'message' => 'wx_openid必须'
            ],
            [
                'input'   => $request['address_id'],
                'require' => true,
                'validator' => 'number',
                'message' => 'address_id必须'
            ],
            [
                'input'     => $request['true_name'],
                'validator' => 'chinese_name',
                'message'   => '姓名必须'
            ],
            [
                'input'     => $request['address_tel'],
                'validator' => 'mobile',
                'message'   => '手机号格式不正确'
            ],
            [
                'input'   => $request['province_id'],
                'require' => true,
                'validator' => 'number',
                'message' => '省必须'
            ],
            [
                'input'   => $request['city_id'],
                'require' => true,
                'validator' => 'number',
                'message' => '市必须'
            ],
            [
                'input'   => $request['county_id'],
                'require' => true,
                'validator' => 'number',
                'message' => '区县必须'
            ],
            [
                'input'   => $request['detail_address'],
                'require' => true,
                'message' => '详细地址必须'
            ],
            [
                'input'     => $request['address_default'],
                'require'   => true,
                'validator' => 'range',
                'min'       => 1,
                'max'       => 2,
                'message'   => 'address_default必须为1或2'
            ],
        ];


        $error = $validate->validate();

        if ($error != '') {
            return Output::response($error, -1);
        }
        return $next($request);
    }
}