<?php

namespace Common\Logic;

use MrStock\System\MJC\Control;
use MrStock\System\Orm\Connector\Mysqli;
use MrStock\System\Helper\File;
use MrStock\System\Helper\Config;
use MrStock\System\Orm\Model;
use MrStock\System\MJC\Http\Request;
use MrStock\System\MJC\Validate;
use Common\Helper\FunctionHelper;
use Common\Model\AdminModel;
use Common\Model\AdminroleModel;
use Common\Model\RoleModel;
use Common\Model\AdminbussinessteamModel;
use MrStock\System\Helper\Arr;

//服务
class AuthLogic
{
    public function isAuth($request)
    {
        $model = new Model();
        $admin_id = $request['admin_id'];
        $key = $request['key_token'];
        $site = $request['check_site'];
        $v = $request['check_v'] ? : "app";
        $c = $request['check_c'];
        $a = $request['check_a'];
        $apicode = md5(strtolower($site . $v . $c . $a));
        $where = ["admin_id" => $admin_id];
        $admininfo = $model->table('admin')->where($where)->find();

        if (empty($admininfo)) {
            throw new \Exception('账号异常', '-1003');
        }

        if ($admininfo['status'] != 1){
            throw new \Exception('您的权限已被禁用，如有疑问请联系管理员', '-1003');
        }

        $where = ["admin_id" => $admin_id, "`key`" => $key];
        $res = $model->table('admin_token')->where($where)->find();
        if (empty($res['admin_id'])) {
            throw new \Exception('你的账号已被他人登录，请联系超管处理或修改密码', '-1003');
        }
        $res = $model->table("api_menu")->where(["apicode" => $apicode])->find();
        if (empty($res)){
            return true;
        }
        $admin_roles = $model->table("admin_role")->where(['admin_id'=>$admin_id,"status"=>1])->limit(false)->select();
        if (!empty($admin_roles)){
            $role_ids = array_column($admin_roles,'role_id');
            $roles = $model->table("role")->where(['role_id'=>["in",$role_ids],'status'=>1])->select();
            if (empty($roles)){
                throw new \Exception('您的权限已被禁用，如有疑问请联系管理员', -403);
            }
            $admin_roles = $model->table("role_api")->where(['role_id'=>["in",$role_ids]])->limit(false)->select();
            if (!empty($admin_roles)){
                $apicode_arr = array_column($admin_roles,"apicode");
                if (in_array($apicode,$apicode_arr)){
                    return true;
                }
            }
        }
        throw new \Exception('没有接口权限', -403);
    }
}