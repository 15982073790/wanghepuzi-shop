<?php
namespace App\Middleware;

use MrStock\System\MJC\MiddlewareClassInterface;
use MrStock\System\MJC\Container;
use MrStock\System\Helper\Output;

class Auth implements MiddlewareClassInterface
{
    /**
     * 应用对象
     * @var App
     */
    protected $app;
    
    /**
     * 请求对象
     * @var App
     */
    protected $request;
    
    public function __construct(){
        $this->app = Container::get("app");
    }
    
    public function handle($request, \Closure $next)
    {
        $this->request = $request;

        if($this->request->testmiddleware)
        {
            return Output::response(-1, "测试中间件");
        }
        return $next($request);
    }
}
