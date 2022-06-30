<?php
namespace Common\Middleware;
use MrStock\System\Helper\Output;
use MrStock\System\MJC\Validate;
class Loudong
{
    public function handle($request, \Closure $next)
    {
        $zuoxihao=$request["zuoxihao"];
        if(!empty($zuoxihao)){
            $validate = new Validate();
            $validate->validateparam = [
                [
                'input' => $request['loudong'],
                'require' => 'true',
                'message' => '请选择楼栋'
                ]
                
            ];
            $error = $validate->validate();
            if ($error != '') {
                return Output::response($error, - 1);
            }
        if(!in_array($request['loudong'], [1,2,3,4])){
                return Output::response("楼栋的格式要不得，请传楼栋id", - 1);
            }
        }
        return $next($request);
    }
}