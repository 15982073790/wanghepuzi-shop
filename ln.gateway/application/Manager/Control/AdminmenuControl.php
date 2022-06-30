<?php

namespace Manager\Control;

use MrStock\System\MJC\Control;
use MrStock\System\Orm\Model;
use MrStock\System\Helper\Arr;
use MrStock\System\Helper\File;
use MrStock\System\Orm\Connector\Mysqli;
use MrStock\System\MJC\Http\Request;
use MrStock\System\MJC\Validate;
use Common\Logic\AdminmenuLogic;

/**
 * @ControlDescription(menuName="")
 */
class AdminmenuControl extends Control
{

    /**
     * @OpDescription(whatFor="获取账号菜单",codeMonkey="")
     */
    public function getadminmenuOp(Request $request,Model $model)
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
        $menu_list = $model->table('api_menu')->where(['apicode'=>['in',array_column($apicode_list,'apicode')]])->select();
        $result=Arr::arrayToArrayKey($menu_list, "sitename",1);
        foreach ($result as $key => &$value) {

            $value=Arr::arrayToArrayKey($value, "groupname",1);
            foreach ($value as $key1 => &$value1) {
                $value1=Arr::arrayToArrayKey($value1, "cname",1);
                foreach ($value1 as $key2 => &$value2) {
                    $value2=array_keys(Arr::arrayToArrayKey($value2, "aname",1));
                }
            }

        }
        return $this->json($result);
    }


}