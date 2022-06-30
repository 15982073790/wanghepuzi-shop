<?php
namespace Common\Middleware;
use MrStock\System\Helper\Output;
use MrStock\System\MJC\Validate;

class Zhiyebianhao
{
    public function handle($request, \Closure $next)
    {
        if($request["gangwei"]!=1){

            $validate = new Validate();
            $validate->validateparam = [
               [
                    'input' => $request['zhiyebianhao'],
                    'require' => 'true',
                    'message' => '执业证书编号必须'
                ],
                [
                    'input' => $request['zhiyebianhao'],
                    'require' => 'true',
                    'validator' => 'custom',
                    'regexp' => '/^[A-Z][0-9]{13}$/',
                    'message' => '执业证书编号格式不正确'
                ]
            ];
            $error = $validate->validate();
            if ($error != '') {
                return Output::response($error, - 1);
            }
            
            
        }
        return $next($request);
    }
}