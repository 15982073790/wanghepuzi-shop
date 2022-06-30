<?php

namespace Common\Logic;

use MrStock\System\MJC\Control;
use MrStock\System\Orm\Connector\Mysqli;
use MrStock\System\Helper\File;
use MrStock\System\Helper\Config;
use MrStock\System\Orm\Model;
use MrStock\System\MJC\Http\Request;
use MrStock\System\MJC\Validate;
use MrStock\System\Helper\Arr;

class ApiLogic extends Control
{


    //需要展示的权限点
    public function selectapi($request, $where, $apicodes = [])
    {
        $result = [];

        $appmodel = new Model("api_menu");
        $dataList = $appmodel->table('api_menu,api')
            ->field('api_menu.sitename,api_menu.groupname,api_menu.cname,api_menu.aname,api_menu.apicode,0 as is_have')
            ->join('left')->on('api_menu.apicode=api.apicode')
            ->limit(false)
            ->select();

        if (!empty($dataList) && !empty($apicodes)) {
            foreach ($dataList as $key => &$value) {
                # code...
                $value["is_have"] = in_array($value["apicode"], $apicodes) ? 1 : 0;
            }
        }
        if (!empty($dataList)) {
            $result = Arr::arrayToArrayKey($dataList, "sitename", 1);
            if (!empty($result)) {

                foreach ($result as &$value) {
                    $value = Arr::arrayToArrayKey($value, "groupname", 1);
                    if (!empty($value)) {

                        foreach ($value as &$value1) {
                            $value1 = Arr::arrayToArrayKey($value1, "cname", 1);
                            if (!empty($value1)) {

                                foreach ($value1 as &$value2) {
                                    $value2 = Arr::arrayToArrayKey($value2, "aname");
                                }
                            }
                        }
                    }
                }
                unset($value);
                unset($value1);
            }
//            var_export($result);exit;
        }
        $result = $this->deleteGroupName($result);


        return $result;
    }

    //获取公司层级系统默认权限包对应的apicode
    private function getcompanysysapicode()
    {
        $result = [];
        $appmodel = new Model();
        $dataList = $appmodel->table('role,role_api')
            ->field('role_api.apicode')
            ->join('left')->on('role.role_id=role_api.role_id')
            ->where(['role.datafrom' => 1, 'role.type' => 3, 'role_api.apicode' => ["neq", ""]])
            ->limit(false)
            ->select();

        if (!empty($dataList)) {
            $result = array_keys(Arr::arrayToArrayKey($dataList, "apicode"));
        }

        return $result;

    }

    //隐藏只有一个控制器的分组名
    public function deleteGroupName($data)
    {

        if (!empty($data)) {
            foreach ($data as $key => &$value) {

                # code...
                foreach ($value as $key1 => &$value1) {
                    foreach ($value1 as $key2 => $value2) {
                        if (count($value1) <= 1) {

                            $value1 = $value2;
                        } else {

                        }
                    }

                }
            }
        }
        return $data;
    }
}