<?php


namespace Common\Middleware;

use Common\Logic\AuthLogic;
use MrStock\System\Helper\Config;
use MrStock\System\Helper\Output;

class AuthMiddleware
{
    public function handle($request, \Closure $next)
    {
        try {
             if($request["v"]=="inneruse"){
                 if ($request["inneruse_secretkey"]!=Config::get("inneruse_secretkey")) {
                     throw new \Exception('inneruse_secretkey不正确', '-1002');
                 }
             }
             if ($request["v"]=="manager"){
                 $authLogic = new AuthLogic();
                 $authLogic->isAuth($request);
             }

        } catch (\Exception $e) {
            return Output::response($e->getMessage(), $e->getCode());
        }
        return $next($request);
    }
}