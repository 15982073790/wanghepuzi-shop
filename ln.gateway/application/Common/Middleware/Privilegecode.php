<?php
namespace Common\Middleware;
use MrStock\System\Helper\Output;
use MrStock\System\MJC\Validate;
use MrStock\System\Orm\Model;
class Privilegecode
{
    public function handle($request, \Closure $next)
    {
        if($request["privilegecode"]&&$request["targetadmin_id"]){
            $model=new Model("admin_bussinessteam");
             $res=$model->where(["admin_id"=>$request["targetadmin_id"]])->find();
             
             if(!empty($res)&&!empty($res["privilegecode"])&&$res["privilegecode"]!=$request["privilegecode"]){
                   return Output::response("该用户特权码已经存在，不能编辑了", - 1); 
             }
        }
         
        return $next($request);
    }
}