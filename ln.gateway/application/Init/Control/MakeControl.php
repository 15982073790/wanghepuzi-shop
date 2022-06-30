<?php
//detail rold_id有问题
namespace Init\Control;

use MrStock\System\MJC\Control;
use MrStock\System\Orm\Connector\Mysqli;
use MrStock\System\Helper\File;
use MrStock\System\Helper\Arr;
use MrStock\System\Helper\Config;
use MrStock\System\Orm\Model;
use MrStock\System\MJC\Http\Request;
use MrStock\System\MJC\Validate;
use Common\Helper\FunctionHelper;

//账号服务初始化脚本
class MakeControl extends Control
{
    //创建管理员
    public function addadminOp(Model $model)
    {
        $result = [];
        $username = 'admin';
        $password = "123456";
        $time = time();
        $insertAdminData = ["username" => $username, "password" => md5($password), "itime" => $time];
        $admin_id = $model->table('admin')->insert($insertAdminData);
        $role_id = $model->table('role')->insert(['role_name'=>'超级管理员','itime'=>$time]);
        $model->table('admin_role')->insert(['admin_id'=>$admin_id,'role_id'=>$role_id]);
        $api_list = $model->table('api')->field('apicode')->select();
        $role_api_insert = [];
        foreach ($api_list as $val){
            $role_api_insert[] = ['role_id'=>$role_id,'apicode'=>$val['apicode']];
        }
        $model->table('role_api')->insertAll($role_api_insert);
        $model->table('role_data')->insertAll([['role_id'=>$admin_id,'type'=>1],['role_id'=>$admin_id,'type'=>2]]);
        return $this->json(1);
    }


}