<?php
namespace Common\Middleware;
use MrStock\System\Helper\Output;
use MrStock\System\MJC\Validate;
use MrStock\System\Orm\Model;
class Zuoxihao
{
    public function handle($request, \Closure $next)
    {
        $zuoxihao=$request["zuoxihao"];
        $loudong=$request["loudong"];
        if(!empty($loudong)&&empty($zuoxihao)){
            return Output::response("请填写坐席号", - 1);
        }
        if(!empty($zuoxihao)){
            $validate = new Validate();
            $validate->validateparam = [
                [
                    'input' => $request['zuoxihao'],
                    'require' => 'true',
                    'validator' => 'custom',
                    'regexp' => '/^[1-9][0-9]*$/',
                    'message' => '坐席号格式不正确'
                ]
                
            ];
            $error = $validate->validate();
            if ($error != '') {
                return Output::response($error, - 1);
            }
          
            $model=new Model("admin_bussinessteam");
            if($request["targetadmin_id"]){
                    $res=$model->where(["zuoxihao"=>$request["zuoxihao"],"loudong"=>$request["loudong"],"admin_id"=>["neq",$request["targetadmin_id"]]])->find();
            }else{
                $res=$model->where(["zuoxihao"=>$request["zuoxihao"],"loudong"=>$request["loudong"]])->find();
            }
             
             if(!empty($res)){
                   return Output::response("坐席号重复", - 1); 
             }
        }
        return $next($request);
    }
}