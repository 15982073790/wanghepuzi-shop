<?php

namespace Manager\Control;

use Common\Logic\AdminroleLogic;
use Common\Logic\ApiLogic;
use Common\Model\AdminModel;
use MrStock\System\Helper\Arr;
use MrStock\System\Helper\Config;
use MrStock\System\Helper\File;
use MrStock\System\MJC\Control;
use MrStock\System\MJC\Facade\Log;
use MrStock\System\MJC\Http\Request;
use MrStock\System\MJC\Validate;
use MrStock\System\Orm\Connector\Mysqli;
use MrStock\System\Orm\Model;
use Common\Helper\FunctionHelper;

/**
 * @ControlDescription(menuName="职务权限",cGroupName="权限管理")
 */
class AdminroleControl extends Control
{
    public $middleware = [
        'control' => [],
        'selectroleuserOp' => [
            'Common\Middleware\Role'
        ]
    ];

    /**
     * @OpDescription(whatFor="查询角色",menuName="职务权限",codeMonkey="")
     */
    public function selectroleOp(Request $request)
    {

        $curpage=$request["curpage"]?$request["curpage"]:1;
        $pagesize=$request["pagesize"]?$request["pagesize"]:20;
        $lib = new AdminroleLogic();
        try {
            $res = $lib->selectrole($request->param,'','');

        } catch (\Exception $ex) {
            $res = [];
        }
        $res = FunctionHelper::morePage($res['list'],$res['total_num'],$curpage,$pagesize);
        return $this->json($res, 1);
    }

    /**
     * @OpDescription(whatFor="编辑角色",menuName="编辑",codeMonkey="")
     */
    public function updateroleOp(Request $request)
    {
        $appmodel = new Model("role");
        $res = $appmodel->where(["role_id" => $request["role_id"]])->update(["role_name" => $request["role_name"], "mark" => $request["mark"], 'utime' => time()]);
        if ($res <= 0) {
            return $this->json("失败", -1);
        }
        return $this->json("成功", 1);
    }

    /**
     * @OpDescription(whatFor="停用/启用",menuName="停用/启用",codeMonkey="")
     */
    public function updatestatusOp(Request $request,AdminModel $adminModel)
    {
        $appmodel = new Model("role");
        $appmodel->beginTransaction();
        $res = $appmodel->where(["role_id" => $request["role_id"]])->update(["status" => $request["status"], 'utime' => time()]);
        if ($res <= 0) {
            $appmodel->rollback();
            return $this->json("失败", -1);
        }
        $data = [
            'role_id' => $request["role_id"],
            'admin_id' => $request["admin_id"],
            'admin_name' => $adminModel->getAdminInfo($request["admin_id"])['username'],
            'handle' => $request["status"] == 1 ? '启用' : '停用',
            'reason' => $request["reason"],
            'itime' => time(),
            'status' => $request["status"],
        ];
        $res = $appmodel->table('role_log')->insert($data);
        if ($res <= 0) {
            $appmodel->rollback();
            return $this->json("失败", -1);
        }
        $appmodel->commit();
        return $this->json("成功", 1);

    }

    /**
     * @OpDescription(whatFor="删除",menuName="删除",codeMonkey="")
     */
    public function deleteroleOp(Request $request)
    {
        $appmodel = new Model();
        $res = $appmodel->table('role')->where(['role_id'=>$request['role_id'],'status'=>2])->find();
        if (empty($res)){
            return $this->json("需要先停用职务才能进行删除操作", -1);
        }
        $res = $appmodel->table('admin_role')->where(['role_id'=>$request['role_id']])->select();
        if (!empty($res)){
            return $this->json("需要先取消绑定职务权限的账号才能进行删除操作", -1);
        }
        $res = $appmodel->table('role')->where(["role_id" => $request["role_id"]])->update(["datastatus"=>0]);
        if ($res > 0) {
            return $this->json("成功", 1);
        } else {
            return $this->json("失败", -1);
        }
    }
    
    /**
     * @OpDescription(whatFor="新增职务",menuName="新增职务",codeMonkey="")
     */
    public function addroleOp(Request $request,Model $model)
    {
        $obj_validate = new Validate ();
        $obj_validate->validateparam = array(
            array("input" => $request ["role_name"], "require" => "true", "message" => '名称不能为空'),
            array("input" => $request ["mark"], "require" => "true", "message" => '备注不能为空'),
        );
        $error = $obj_validate->validate();
        if ($error != '') {
            return $this->json($error, -1);
        }
        $role_name = $request ["role_name"];
        $mark = $request ["mark"];
        $re = $model->table('role')->where(['role_name'=>$role_name])->find();
        if ($re){
            return $this->json($role_name.'已存在，请重新输入',-1);
        }
        $re = $model->table('role')->insert(['role_name'=>$role_name,'mark'=>$mark]);
        if ($re <= 0){
            return $this->json('新增失败',-1);
        }
        return $this->json('成功');
    }

    /**
     * @OpDescription(whatFor="设置权限",menuName="设置权限",codeMonkey="")
     */
    public function setemptyOp(Request $request,Model $model)
    {

    }

    /**
     * @OpDescription(whatFor="操作权限",menuName="操作权限",codeMonkey="")
     */
    public function setpointOp(Request $request)
    {

        $appmodel = new Model("role_api");
//        $oldData = $appmodel->where(['role_id' => $request->role_id])->select();
        $appmodel->beginTransaction();
        $res = $appmodel->where(['role_id' => $request->role_id])->delete();
        if ($res <= 0) {
            $appmodel->rollback();
            return $this->json('操作不成功', -1);
        }
        $insertData = [];
        $apicode = explode(',', $request->apicode);
        if (!empty($apicode)) {
            foreach ($apicode as $key => $value) {
                # code...
                $insertData[$key]["role_id"] = $request->role_id;
                $insertData[$key]["apicode"] = $value;
            }
            $res = $appmodel->insertAll($insertData);
            if ($res <= 0) {
                $appmodel->rollback();
                return $this->json('操作不成功', -1);
            }
        }

        $appmodel->commit();
/*//        比对减少的权限.将受到影响的admin_id重新登录
        $old_apicode = array_column($oldData,'apicode');
        $new_apicode = explode(',',$request['apicode']);
        $delapicode = [];
        foreach ($old_apicode as $old){
            if (!in_array($old,$new_apicode)){
                array_push($delapicode,$old);
            }
        }
        $appmodel = new Model("prichange");
//        查询已经有的.登录成功的时候要全部插入一遍.
        $isin = $appmodel->where(['apicode'=>['in',$delapicode],'adminid'=>-1])->select();
        $isin  = array_column($isin,'apicode');
        $toadd = [];
        foreach ($delapicode as $v){
            if (!in_array($v,$isin)){
                array_push($toadd,['apicode'=>$v]);
            }
        }
        $appmodel->insertAll($toadd);
//        直接将role_id的所有admin_id下线.
        $appmodel = new Model("admin_role");
        $roleadminid = $appmodel->where(['role_id'=>$request->role_id])->select();
        $roleadminid = array_column($roleadminid,'admin_id');
        $tokenmodel = new Model('admin_token');
        $tokenmodel->where(['admin_id'=>['in',$roleadminid]])->delete();*/
        return $this->json('操作成功', 1);

    }

    /**
     * @OpDescription(whatFor="查询角色包权限点（单条）设置权限页面专用",codeMonkey="")
     */
    public function selectroleapiOp(Request $request)
    {
        $lib = new ApiLogic();
        $apicodes = [];
        $model = new Model("role_api");
        $res = $model->where(["role_id" => $request->role_id])->limit(false)->select();
        if (!empty($res)) {
            $apicodes = array_column($res, "apicode");
        }
        $res = $lib->selectapi($request->param, [], $apicodes);

        return $this->json(['list' => $res], 1);
    }

    /**
     * @OpDescription(whatFor="设置数据权限",menuName="数据权限",codeMonkey="")
     */
    public function setdatapointOp(Request $request)
    {

        $appmodel = new Model("role_data");
        $appmodel->beginTransaction();
        $res = $appmodel->where(['role_id' => $request->role_id])->delete();

        if (!$res) {
            $appmodel->rollback();
            return $this->json('操作不成功', -1);
        }

        $data_id = explode(',', $request->data_id);




        if (!empty($data_id)) {
            $insertData = [];
            foreach ($data_id as $key => $value) {
                # code...
                $tmp=[];
                $tmp["role_id"] = $request->role_id;
                $tmp["type"] = $value;
                array_push($insertData ,$tmp);
            }

            $res = $appmodel->insertAll($insertData);

            if (!$res) {
                $appmodel->rollback();
                return $this->json('操作不成功', -1);
            }
        }

        $appmodel->commit();
        return $this->json('操作成功', 1);

    }

    /**
     * @OpDescription(whatFor="查询角色包数据权限点（单条）设置权限页面专用",codeMonkey="")
     */
    public function selectroleapidataOp(Request $request)
    {

        $model = new Model("role_data");
        $res = $model->where(["role_id" => $request->role_id])->select();

        $types = [];
        if (!empty($res)) {
            $types = array_column($res, "type");
        }

        $result = Config::get("apidata");

        if (!empty($types)) {
            foreach ($result as $key => &$value) {

                $value["is_have"] = !in_array($value["data_id"], $types) ? 0 : 1;

            }
        }

        return $this->json(['list' => $result], 1);
    }

    /**
     * @OpDescription(whatFor="成员列表",menuName="成员列表",codeMonkey="")
     */
    public function adminlistOp(Request $request,Model $model)
    {
        $curpage=$request["curpage"]?$request["curpage"]:1;
        $pagesize=$request["pagesize"]?$request["pagesize"]:20;
        $totalNum = $model->table('admin_role')->where(['role_id'=>$request['role_id']])->count();////总条数

        $dataList = $model->table('admin_role,admin')->on('admin_role.admin_id = admin.admin_id')->field(['truename','username','admin_role.itime'])->where(['role_id'=>$request['role_id']])->page($curpage,$pagesize)->order('admin_role.itime desc')->select();
        $res = FunctionHelper::morePage($dataList,$totalNum,$curpage,$pagesize);
        return $this->json($res);
    }

    /**
     * @OpDescription(whatFor="操作记录",menuName="操作记录",codeMonkey="")
     */
    public function handlelistOp(Request $request,Model $model)
    {
        $curpage=$request["curpage"]?$request["curpage"]:1;
        $pagesize=$request["pagesize"]?$request["pagesize"]:20;
        $totalNum = $model->table('role_log')->where(['role_id'=>$request['role_id']])->count();////总条数

        $dataList = $model->table('role_log')->field(['admin_name','handle','reason','itime'])->where(['role_id'=>$request['role_id']])->page($curpage,$pagesize)->order('itime desc')->select();

        return $this->json(['list'=>$dataList,'total_num'=>$totalNum,'page_count'=>ceil($totalNum/$pagesize)]);
    }

    public function publiclistOp(Request $request,Model $model)
    {
        try {
            $res = $model->table('role')->where(['status'=>1])->limit(false)->select();
        } catch (\Exception $ex) {
            $res = [];
        }

        return $this->json($res, 1);
    }
}