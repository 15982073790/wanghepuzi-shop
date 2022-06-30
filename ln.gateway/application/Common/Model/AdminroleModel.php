<?php

namespace Common\Model;

use MrStock\System\Orm\Connector\Mysqli;
use MrStock\System\Orm\Model;
use Common\Helper\FunctionHelper;
use MrStock\System\Helper\Arr;

class AdminroleModel extends Model
{
    public function __construct()
    {
        parent::__construct('admin_role');
    }


    public function getRoleByAdminId($admin_id)
    {
        $result = [];
        $adminlist = $this->field("role_id,admin_id")->where(["admin_id" => $admin_id])->limit(false)->select();

        if (!empty($adminlist)) {
            $result = array_column($adminlist, "role_id");
        }
        return $result;

    }

    public function getRoleIdByAdminIds($admin_ids)
    {
        return $this->field("role_id,admin_id")->where(["admin_id" => ["in", $admin_ids]])->limit(false)->select();
    }

    public function getAdmincountByRoleIds($role_ids)
    {
        $role_ids = implode(',', $role_ids);
        $sql = "SELECT
        role_id,
        count(admin_id) as admin_num
        FROM
        `gs_admin_role`
        WHERE
        role_id IN ({$role_ids})
        GROUP BY
        role_id";
        $res = mysqli::getAll($sql);
        return $res;
    }

    public function gethasapi($admin_id, $v_arr = ["app", "manager"])
    {
        $appmodel = new Model("admin");
        $res = $appmodel->where(["admin_id" => $admin_id, "datastatus" => 1])->find();

        if (empty($res)) {
            return $this->json("账号异常", -1);
        }

        $model = new Model("api");
        $res = $model->field("apicode")->where(["v" => ["in", $v_arr]])->limit(false)->select();

        $iszdy = $model->table('admin_role,role')
            ->field(' role.role_id')
            ->join('inner')->on('admin_role.role_id=role.role_id')
            ->where(['admin_role.admin_id' => $admin_id])
            ->find();

        if (!empty($iszdy)) {
            $res = [];
        }

        return $res;
    }

    private function getywaip()
    {
        $result = [];
        $model = new Model();
        $where['role.datafrom'] = 1;
        $where['role.role_code'] = ["not in", ["fw", "fwboss", "nrbj", "zskf"]];
        $apicodes = $model->table('role,role_api')
            ->field('role_api.apicode')
            ->join('inner')->on('role.role_id=role_api.role_id')
            ->where($where)
            ->limit(false)
            ->select();

        if (!empty($apicodes)) {
            $result = array_keys(Arr::arrayToArrayKey($apicodes, "apicode"));

        }
        return $result;
    }
}