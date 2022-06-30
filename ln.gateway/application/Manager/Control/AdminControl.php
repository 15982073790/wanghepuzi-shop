<?php

namespace manager\Control;

use Common\Data\AdminData;
use MrStock\System\MJC\Control;
use MrStock\System\Orm\Model;

use MrStock\System\MJC\Http\Request;
use MrStock\System\MJC\Validate;
use MrStock\System\Helper\RpcRequest;
use Common\Logic\AdminLogic;
use Common\Helper\FunctionHelper;
/**
 * @ControlDescription(menuName="用户列表",cGroupName="权限管理")
 */
class AdminControl extends Control
{
    public $middleware = [
        'control' => [],
        'changepasswordOp' => [
            'Common\Middleware\Password'
        ]

    ];
    /**
     * @OpDescription(whatFor="用户列表",menuName="用户列表",codeMonkey="")
     */

    public function selectlistOp(Request $request,Model $model)
    {

        $curpage = $request['curpage'] ? $request['curpage'] : 1;
        $pagesize = $request['pagesize'] ? $request['pagesize'] : 20;
        $lib = new AdminLogic();
        $res = $lib->selectlist($request->param);
        $res = FunctionHelper::morePage($res['list'],$res['total_num'],$curpage,$pagesize);
        return $this->json($res, 1);
    }
    /**
     * @OpDescription(whatFor="编辑",menuName="编辑",codeMonkey="")
     */
    public function updateinfoOp(Request $request,Model $model)
    {
        $check = AdminData::checkUserName($request['username']);
        if ($check && $request["targetadmin_id"] != $check){
            return $this->json("用户名已存在，请重新输入", -1);
        }
        $admin_tel = $model->table('admin')->where(['tel'=>$request['tel']])->find();
        if(!empty($admin_tel) && $request["targetadmin_id"] != $admin_tel['admin_id']){
            return $this->json("手机号已存在，请重新输入", -1);
        }
        $updata_data = AdminData::getup($request->param);
        $res = $model->table("admin")->where(["admin_id" => $request["targetadmin_id"]])->update($updata_data);
        if ($res <= 0) {
            return $this->json("编辑失败", -1);
        }
        return $this->json("成功", 1);
    }

    /**
     * @OpDescription(whatFor="新增",menuName="新增用户",codeMonkey="")
     */
    public function addOp(Request $request,Model $model)
    {
        $check = AdminData::checkUserName($request['username']);
        if ($check){
            return $this->json("用户名已存在，请重新输入", -1);
        }
        $admin_tel = $model->table('admin')->where(['tel'=>$request['tel']])->find();
        if(!empty($admin_tel)){
            return $this->json("手机号已存在，请重新输入", -1);
        }
        $insert_data = AdminData::getadd($request->param);
        $res = $model->table("admin")->insert($insert_data);
        if ($res <= 0) {
            return $this->json("失败", -1);
        }
        return $this->json("成功", 1);
    }

    /**
     * @OpDescription(whatFor="绑定职务",menuName="绑定职务",codeMonkey="")
     */
    public function bindroleOp(Request $request,Model $model)
    {
        $obj_validate = new Validate ();
        $obj_validate->validateparam = array(
            array("input" => $request ["targetadmin_id"], "require" => "true", "message" => '用户id不能为空'),
            array("input" => $request ["role_id"], "require" => "true", "message" => '角色id不能为空'),
        );
        $error = $obj_validate->validate();
        if ($error != '') {
            return $this->json($error, -1);
        }
        $targetadmin_id = $request['targetadmin_id'];
        $role_ids = explode(',',$request ["role_id"]);
        $insertDta = [];
        $time = time();
        foreach ($role_ids as $val){
            $insertDta[] = ['admin_id'=>$targetadmin_id,'role_id'=>$val,'itime'=>$time];
        }
        $model->beginTransaction();
        $res = $model->table("admin_role")->where(['admin_id'=>$request['targetadmin_id']])->delete();
        if ($res <= 0) {
            $model->rollback();
            return $this->json("失败", -1);
        }
        $res = $model->table("admin_role")->insertAll($insertDta);
        if ($res <= 0) {
            $model->rollback();
            return $this->json("失败", -1);
        }
        $model->commit();
        return $this->json("成功", 1);
    }
    /**
     * @OpDescription(whatFor="停用/启用",menuName="停用/启用",codeMonkey="")
     */
    public function updatestatusOp(Request $request,Model $model)
    {
        $obj_validate = new Validate ();
        $obj_validate->validateparam = array(
            array("input" => $request ["targetadmin_id"], "require" => "true", "message" => '用户id不能为空'),
            array("input" => $request ["status"], "require" => "true", "message" => '角色id不能为空'),
        );
        $error = $obj_validate->validate();
        if ($error != '') {
            return $this->json($error, -1);
        }
        $res = $model->table("admin")->where(['admin_id'=>$request['targetadmin_id']])->update(['status'=>$request ["status"]]);
        if ($res <= 0) {
            return $this->json("失败", -1);
        }
        return $this->json("成功", 1);
    }
    /**
     * @OpDescription(whatFor="删除",menuName="删除",codeMonkey="")
     */
    public function deleteadminOp(Request $request,Model $model)
    {
        $obj_validate = new Validate ();
        $obj_validate->validateparam = array(
            array("input" => $request ["targetadmin_id"], "require" => "true", "message" => '用户id不能为空'),
        );
        $error = $obj_validate->validate();
        if ($error != '') {
            return $this->json($error, -1);
        }
        $res = $model->table("admin")->where(['admin_id'=>$request['targetadmin_id'],'status'=>2])->find();
        if (empty($res)){
            return $this->json("需要先停用账户才能进行删除操作", -1);
        }
        $res = $model->table("admin")->where(['admin_id'=>$request['targetadmin_id']])->update(['datastatus'=>0]);
        if ($res <= 0) {
            return $this->json("失败", -1);
        }
        return $this->json("成功", 1);
    }
    /**
     * @OpDescription(whatFor="根据工号获取员工Id",menuName="",codeMonkey="")
     */
    public function memberidbyjobnumberOp(Request $request,MemberModel $new_member_model){
        $param = $request->param;
        $job_number = $param['job_number'];
        $member_find = $new_member_model->findIdByJobNumber($job_number);
        $res = $member_find;
        return $this->json($res);
    }

    /**
     * @OpDescription(whatFor="根据手机号获取领导Id",menuName="",codeMonkey="")
     */
    public function leaderidbytelOp(Request $request,LeaderModel $new_leader_model){
        $param = $request->param;
        $tel = $param['tel'];
        $leader_find = $new_leader_model->findIdByTel($tel);
        $res = $leader_find;
        return $this->json($res);
    }
}
