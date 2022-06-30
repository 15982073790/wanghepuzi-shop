<?php
/**
 * User: duyang
 * Date: 2018/12/20 15:54
 */

namespace Common\Middleware;


use MrStock\System\Helper\Output;
use MrStock\System\MJC\Validate;

/**
 * Class Mobile
 * @package App\Middleware\Common
 * 验证手机号
 */
class Truename
{
    public function handle($request, \Closure $next)
    {

        $validate = new Validate();
        $validate->validateparam = [
           [
                'input' => $request['truename'],
                'require' => 'true',
                'validator' => 'custom',
                'regexp' => '/^[\x{4e00}-\x{9fa5}]{1,10}$/u',
                'message' => '请填写真实姓名'
            ]
        ];
        $error = $validate->validate();
        if ($error != '') {
            return Output::response($error, - 1);
        }

        return $next($request);
    }
}