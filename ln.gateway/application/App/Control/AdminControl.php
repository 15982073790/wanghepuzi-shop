<?php

namespace App\Control;
 
use Common\Helper\FunctionHelper;
use Common\Model\AdminbussinessteamModel;
use MrStock\System\Helper\Arr;
use MrStock\System\Helper\Config;
use MrStock\System\Helper\File;
use MrStock\System\MJC\Control;
use MrStock\System\MJC\Http\Request;
use MrStock\System\MJC\Validate;
use MrStock\System\Orm\Connector\Mysqli;
use MrStock\System\Orm\Model;

class AdminControl extends Control
{
    /**
     * @OpDescription(whatFor="后台登录",codeMonkey="")
     */
    public function loginOp(Request $request)
    {
        // 登录验证
        $obj_validate = new Validate ();
        $obj_validate->validateparam = array(
            array("input" => $request ["password"], "require" => "true", "message" => '密码不能为空'),
            array("input" => $request ["username"], "require" => "true", "message" => '用户名或密码不能为空'),
        );
        $error = $obj_validate->validate();
        if ($error != '') {
            return $this->json($error, -1);
        }

        $where = ["username" => $request["username"], "password" => md5($request["password"])];

        $model = new Model("admin");
        $adminInfo = $model->field('admin_id,username,status,img')->where($where)->find();

        if (empty($adminInfo)) {
            return $this->json("你输入的帐号或密码不正确，请重新输入", -1);
        } elseif ($adminInfo["status"] == 2) {
            return $this->json("账号被停用，请联系管理员", -1);
        }


        //记录用户登录统计数据，不用事务，不保证数据的准确性
        $model = new Model("admin");
        $where = ["admin_id" => $adminInfo["admin_id"]];
        $model->where($where)->update(["lastlogintime" => time(), "utime" => time()]);
        $model->where($where)->setInc("loginnum", 1);
        //end
        $model = new Model("admin_token");
        $where = ["admin_id" => $adminInfo["admin_id"]];
        $res = $model->field('`key`')->where($where)->find();
        $token = FunctionHelper::get_hash();
        if (empty($res)) {
            $insertData = ["admin_id" => $where["admin_id"], "`key`" => $token];
            $res = $model->insert($insertData);
        } else {
            $updateData = ["`key`" => $token];
            $res = $model->where($where)->update($updateData);
        }
        if ($res > 0) {
            $img = '';
            if (!empty($adminInfo["img"])){
                $img = Config::get("static_url").$adminInfo["img"];
            }
            return $this->json(["admin_id" => $where["admin_id"], "username" => $adminInfo["username"], "key_token" => $token,'img'=>$img], 1);
        } else {
            return $this->json("登录失败，token异常", -1);
        }
    }


}