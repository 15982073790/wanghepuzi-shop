<?php

namespace Common\Model;


use Common\Data\RoleData;
use MrStock\System\Helper\Tool;
use MrStock\System\Orm\Model;

class RoleModel extends Model
{
    public function __construct()
    {
        parent::__construct('role');
    }

    public function getRoleNameByRoleIds($role_ids)
    {
        $result = $this->field("role_id,role_name")->where(["role_id" => ["in", $role_ids]])->limit(false)->select();

        return $result;
    }

    /**
     * 新增角色
     */
    public function addrole($request)
    {
        $insertData = RoleData::getadd($request);
        return $this->insert($insertData);
    }

    /**
     * 编辑角色
     */
    public function editrole($request)
    {
        $Data = RoleData::getup($request);
        return $this->where(["role_id" => $request["role_id"]])->update($Data);
    }


    /**
     * where构建器
     */
    public function getwhere($request)
    {
        $like = [];
        $equal = ["datastatus", "role_name","datafrom"];
        $gt = [];
        $lt = [];
        $result = [];
        if (!empty($like)) {
            foreach ($like as $key => $value) {
                if (trim($request[$value])) {
                    $result[$value] = ['like', "%" . trim($request[$value]) . "%"];
                }
            }
        }
        if (!empty($equal)) {
            foreach ($equal as $key => $value) {

                if ($request[$value] || $request[$value] === "0") {

                    $result[$value] = $request[$value];
                }
            }
        }
        if (!empty($gt)) {
            foreach ($gt as $key => $value) {
                if ($request[$value]) {
                    $result[str_replace("_s", "", $value)] = ["gt", $request[$value]];
                }
            }
        }
        if (!empty($lt)) {
            foreach ($lt as $key => $value) {
                if ($request[$value]) {
                    if (isset($result[str_replace("_e", "", $value)])) {
                        $result[str_replace("_e", "", $value)] = ["between", [$result[str_replace("_e", "", $value)][1], $request[$value]]];
                    } else {
                        $result[str_replace("_e", "", $value)] = ["lt", $request[$value]];
                    }

                }
            }
        }

        $admin_ids = $this->getadminwhere($request);
        if (!empty($admin_ids)) {
            $result["role_id"] = ["in", $admin_ids];
        }

        return $result;
    }

    private function getroleidbyusername($username)
    {

        $res = $this->table('role,admin')
            ->field('role.role_id')
            ->join('left')->on('admin.admin_id=role.admin_id')
            ->where(['admin.username' => trim($username)])
            ->limit(false)
            ->select();

        return $res;
    }

    private function getroleidbyapicname($cname)
    {
        $res = $this->table('role_api,api_menu')
            ->field('role_api.role_id')
            ->join('left')->on('role_api.apicode=api_menu.apicode')
            ->where(['api_menu.cname' => trim($cname)])
            ->limit(false)
            ->select();
        return $res;
    }


    /**
     * where构建器admin
     */
    private function getadminwhere($request)
    {

        $result = [];
        $adminidfind = [["table" => "", "param" => "admin_username", "where" => [], "func" => "getroleidbyusername"],
            ["table" => "", "param" => "role_api_name", "where" => [], "func" => "getroleidbyapicname"]];
        $admin_ids_arr = [];
        foreach ($adminidfind as $key => $value) {
            # code...
            if ($request[$value["param"]]) {
                if ($value["func"] =='getroleidbyusername') {
                    $res = $this->getroleidbyusername($request[$value["param"]]);
                }elseif($value["func"] =='getroleidbyapicname'){
                    $newModel = new Model();
                    $menuWhere ="sitename='{$request[$value['param']]}'
                                or groupname='{$request[$value['param']]}'
                                or cname='{$request[$value['param']]}'
                                or aname='{$request[$value['param']]}'";
                    $res = $newModel ->table('api_menu,role_api')
                        ->field('role_id')
                        ->join('inner')
                        ->on('role_api.apicode = api_menu.apicode')
                        ->where($menuWhere)
                        ->select();
                }else {
                    $role = new Model($value["table"]);
                    $res = $role->field("admin_id")->where($value["where"])->select();
                }

                if (!empty($res)) {
                    $admin_ids_arr[] = array_column($res, "role_id");
                } else {
                    throw new \Exception("查询无数据");
                }
            }
        }
        $admin_ids = $admin_ids_arr[0];
        if (count($admin_ids_arr) > 1) {
            foreach ($admin_ids_arr as $key => $value) {
                # code...
                $admin_ids = array_intersect($admin_ids, $value);
                if (empty($admin_ids)) {
                    throw new \Exception("查询无数据");
                }
            }
        }

        $result = $admin_ids;

        return $result;
    }


}