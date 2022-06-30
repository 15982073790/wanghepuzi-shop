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
use MrStock\System\Helper\RpcRequest;

//服务
class AdminLogic
{


    public $admin_ids = false;

    public function selectByBus($request)
    {
        $f = ['zuoxihao', 'zhiyebianhao'];
        $w = [];
        foreach ($f as $v) {
            if (isset($request[$v]) && strlen(strval($request[$v])) > 0) {
                $w[$v] = $request[$v];
            }
        }
        if (sizeof($w) > 0) {
            $Adminbussinessteam = new AdminbussinessteamModel();
            $this->admin_ids = array_column($Adminbussinessteam->where($w)->limit(false)->field('admin_id')->select(), 'admin_id');
        }
        return $this;
    }


    public function selectlist($request,$curpage,$pagesize,$where,$order)
    {

        $where = empty($where) ? ' 1 = 1 ' : $where;
        $order = empty($order) ? "itime desc" : $order;
        $model = new Model();
        if ($request['tel_name']){
            $tel_name = $request['tel_name'];
            $where .= " and (username like '%".$tel_name."%'"." or truename like '%".$tel_name."%'"." or tel = '".$tel_name."')";
        }
        if ($request['role_id']){
            $admin_id = $model->table('admin_role')->where(['role_id'=>$request['role_id']])->select();
            if (!empty($admin_id)){
                $admin_id_arr = implode(',',array_column($admin_id,'admin_id'));
                $where .= " and admin_id in ($admin_id_arr)";
            }else{
                $where = " 0 = 1 ";
            }
        }
        if ($request['status']){
            $where .= " and status = ".$request['status'];
        }
        if ($request['duty_type']){
            $where .= " and duty_type = ".$request['duty_type'];
        }
        $appmodel = new AdminModel();
        $totalNum = $appmodel->where($where)->count();////总条数

        $dataList = $appmodel->where($where)->order($order)->page($curpage, $pagesize)->select();

        if (!empty($dataList)) {

            $admin_ids = array_column($dataList, "admin_id");

            $role_names = $this->get_role_names($admin_ids);
            foreach ($dataList as $key => &$value) {
                # code...
                $value["privilegecode"] = "";
                $value["gangwei"] = "";
                $value["role_ids"] = $role_names[$value["admin_id"]]["role_ids"];;
                $value["role_names"] = $role_names[$value["admin_id"]]["role_names"];
            }
        }

        return ['list' => $dataList, 'total_num' => $totalNum];
    }

    private function get_role_names($admin_ids)
    {
        $result = [];
        $model = new AdminroleModel();
        $result = $model->getRoleIdByAdminIds($admin_ids);
        $role_ids = array_unique(array_column($result, "role_id"));

        $model = new RoleModel();
        $role_id = $model->getRoleNameByRoleIds($role_ids);
        $role_hash = Arr::arrayToArrayKey($role_id, "role_id");

        $result = Arr::arrayToArrayKey($result, "admin_id", 1);

        if (!empty($result)) {
            foreach ($result as $key => &$value) {
                foreach ($value as $key1 => &$value1) {
                    # code...
                    $value1["role_name"] = $role_hash[$value1["role_id"]]["role_name"];
                }
                $value["role_names"] = implode(',', array_column($value, "role_name"));
                $value["role_ids"] = implode(',', array_column($value, "role_id"));
            }
        }
        return $result;
    }
    //冻结账号有特殊操作site=solution&c=Teacher&a=forbiddenaccount&v=inneruse
    //c=Live&a=closeteacher&v=Inneruse&site=traders
    public function changestatus($request)
    {
        $result = [];
        if (empty($request['datastatus'])) $request['datastatus'] = 0;
        if (!in_array($request["datastatus"], [0, 1])) {

            return ["code" => -1, "message" => "datastatus值不对"];
        }
        if (empty($request["targetadmin_id"])) {
            return ["code" => -1, "message" => "目标用户不能为空"];
        }
        $appmodel = new Model("admin");
        $res = $appmodel->where(["admin_id" => $request["targetadmin_id"]])->update(["datastatus" => $request["datastatus"], 'utime' => time()]);

        if (!empty($res)) {
            if ($request['datastatus'] == 0) {
                //不做异常处理，由目标站点记录并且统一线下操作
                \MrstockCloud\Client\MrstockCloud::solution()->inneruse()->v()
                    ->Teacher()
                    ->forbiddenaccount(["admin_id" => $request["targetadmin_id"]])
                    ->request();
                \MrstockCloud\Client\MrstockCloud::traders()->inneruse()->v()
                    ->Live()
                    ->closeteacher(["admin_id" => $request["targetadmin_id"]])
                    ->request();

            }
            return ["code" => 1, "message" => "成功"];
        } else {
            return ["code" => -1, "message" => "失败"];

        }
    }

    /**
     *
     */
    public function getAdminBusInfo($admin_id)
    {
        $AdminbussinessteamModel = new AdminbussinessteamModel();
        $data = $AdminbussinessteamModel->getAdminBussinessteamInfo(['admin_id' => $admin_id], 'tag,admin_id');
        return $data;
    }


}