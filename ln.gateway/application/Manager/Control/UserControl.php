<?php

namespace manager\Control;

use MrStock\System\MJC\Control;
use MrStock\System\Orm\Connector\Mysqli;
use MrStock\System\Helper\File;
use MrStock\System\Helper\Config;
use MrStock\System\Orm\Model;
use MrStock\System\MJC\Http\Request;
use MrStock\System\MJC\Validate;
use MrStock\System\Helper\Arr;
use Common\Helper\FunctionHelper;
use Common\Logic\AdminLogic;

class UserControl extends Control
{
    public $middleware = [
        'control' => [],
        'changepasswordOp' => [
            'Common\Middleware\Password'
        ]

    ];

    public function updatepasswordOp(Request $request,Model $model)
    {
        $obj_validate = new Validate ();
        $obj_validate->validateparam = array(
            array("input" => $request ["admin_id"], "require" => "true", "message" => '用户id不能为空'),
            array("input" => $request ["old_password"], "require" => "true", "message" => '旧密码不能为空'),
            array("input" => $request ["new_password"], "require" => "true", "message" => '新密码不能为空'),
        );
        $error = $obj_validate->validate();
        if ($error != '') {
            return $this->json($error, -1);
        }
        $re = $model->table('admin')->where(['admin_id'=>$request->admin_id,'password'=>md5($request ["old_password"])])->find();
        if (empty($re)){
            return $this->json('原密码错误',-1);
        }
        $re = $model->table('admin')->where(['admin_id'=>$request->admin_id])->update(['password'=>md5($request->new_password),'utime'=>time()]);
        if ($re){
            return $this->json('修改成功');
        }else{
            return $this->json('修改失败',-1);
        }
    }

    public function updateinfoOp(Request $request,Model $model)
    {
        $res = $model->table('admin')->where(["admin_id"=>$request["admin_id"]])->update(["img"=>$request["img"]]);
        if ($res > 0) {
            return $this->json("成功");
        } else {
            return $this->json("失败", -1);
        }
    }

    public function addfastmenuOp(Request $request,Model $model)
    {
        $obj_validate = new Validate ();
        $obj_validate->validateparam = array(
            array("input" => $request ["admin_id"], "require" => "true", "message" => '用户id不能为空'),
            array("input" => $request ["apicodes"], "require" => "true", "message" => '角色id不能为空'),
        );
        $error = $obj_validate->validate();
        if ($error != '') {
            return $this->json($error, -1);
        }
        $model->beginTransaction();
        $re = $model->table('fast_menus')->where(['admin_id'=>$request['admin_id']])->delete();
        if ($re<=0){
            $model->rollback();
            return $this->json("失败", -1);
        }
        $apicodes = explode(',',$request ["apicodes"]);
        $insertDta = [];
        foreach ($apicodes as $key=>$val){
            $insertDta[] = ['admin_id'=>$request['admin_id'],'apicode'=>$val,'sort'=>$key+1];
        }
        $re = $model->table('fast_menus')->insertAll($insertDta);
        if ($re<=0){
            $model->rollback();
            return $this->json("失败", -1);
        }
        $model->commit();
        return $this->json("成功");
    }

    public function fastmenuOp(Request $request,Model $model)
    {
        $role_id_list = $model->table('admin_role')->field('role_id')->where(['admin_id'=>$request['admin_id'],'status'=>1])->select();
        if (empty($role_id_list)){
            return $this->json([]);
        }
        $role_list = $model->table('role')->where(['role_id'=>['in',array_column($role_id_list,'role_id')],'status'=>1])->select();
        if (empty($role_list)){
            return $this->json([]);
        }
        $apicode_list = $model->table('role_api')->field('apicode')->where(['role_id'=>['in',array_column($role_id_list,'role_id')]])->select();
        if (empty($apicode_list)){
            return $this->json([]);
        }
        $where = "apicode in ()";
        $appcode_str = '';
        foreach ($apicode_list as $apicode){
            $appcode_str .= "'".$apicode['apicode']."',";
        }
        $appcode_str = trim($appcode_str,',');
        $where = "apicode in ($appcode_str) and cname = aname";
        $menu_list = $model->table('api_menu')->where($where)->select();
        $fast_list = $model->table('fast_menus')->where(['admin_id'=>$request['admin_id']])->select();
        $fast_list_apicode = array_column($fast_list,'apicode');
        $fast_list_sort = array_column($fast_list,'sort','apicode');
        foreach ($menu_list as &$menu){
            $menu['is_have'] = in_array($menu['apicode'],$fast_list_apicode) ? 1 : 0 ;
            $menu['sort'] = $fast_list_sort[$menu['apicode']] ? intval($fast_list_sort[$menu['apicode']]) : 0 ;
        }
        $result=Arr::arrayToArrayKey($menu_list, "sitename",1);

        foreach ($result as $key => &$value) {

            $value=Arr::arrayToArrayKey($value, "groupname",1);
            /*foreach ($value as $key1 => &$value1) {
                $value1=(Arr::arrayToArrayKey($value1, "cname",1));
                foreach ($value1 as $key2 => &$value2) {
                    $value2=Arr::arrayToArrayKey($value2, "aname",1);
                }
            }*/

        }
        return $this->json($result);
    }
}
