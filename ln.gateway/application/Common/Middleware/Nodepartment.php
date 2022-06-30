<?php
namespace Common\Middleware;
use MrStock\System\Helper\Output;
use MrStock\System\MJC\Validate;

class Nodepartment
{
    public function handle($request, \Closure $next)
    {
        $department_id=intval($request['department_id']);
        if($department_id){
            return Output::response("不能查看部门数据", - 1);
        }

        return $next($request);
    }
}