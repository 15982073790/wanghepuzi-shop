<?php

namespace Inneruse\Control;

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
use Common\Logic\AdminLogic;
use Common\Model\AdminModel;
use MrStock\System\MJC\Facade\Log;
use CxtCloud\Client\CxtClient;

class AdminControl extends Control
{
    /**
     * @OpDescription(whatFor="根据后台账号id集合获取账号名字信息",codeMonkey="")
     */
    public function selectbyadminidsOp(Request $request)
    {
        // 登录验证
        $obj_validate = new Validate ();
        $obj_validate->validateparam = array(
            array("input" => $request ["admin_ids"], "require" => "true", "message" => '所查询的账号id集合不能为空')
        );
        $error = $obj_validate->validate();
        if ($error != '') {
            return $this->json($error, -1);
        }
        $admin_ids = explode(',', $request ["admin_ids"]);
        if (count($admin_ids) > 100) {
            return $this->json("id集合超过限制", -1);
        }
        $model = new Model("admin");
        $adminlist = $model->field('admin_id,username,truename,tel,img')->where(["admin_id" => ["in", $admin_ids]])->limit(false)->select();
        return $this->json(['list' => $adminlist], 1);
    }

    /**
     * @OpDescription(whatFor="根据账号昵称查询账号ID",codeMonkey="")
     */
    public function getadminidbyusernameOp(Request $request)
    {
        $result = [];
        $model = new Model("admin");
        $adminInfo = $model->field('admin_id,username')->where(["username" => $request["username"]])->find();
        if (!empty($adminInfo)) {
            $result["admin_id"] = $adminInfo["admin_id"];
        }
        return $this->json($result, 1);
    }

    /**
     * @OpDescription(whatFor="根据后台账号id获取账号数据权限",codeMonkey="")
     */
    public function databyadminidOp(Request $request)
    {
        $result = Config::get("apidata");

        // 登录验证
        $obj_validate = new Validate ();
        $obj_validate->validateparam = array(
            array("input" => $request ["admin_id"], "require" => "true", "message" => '所查询的账号id不能为空')
        );
        $error = $obj_validate->validate();
        if ($error != '') {
            return $this->json($error, -1);
        }
        $sql = "SELECT
              a.admin_id,c.type
            FROM
              `gs_admin` a
            INNER JOIN gs_admin_role b ON a.admin_id = b.admin_id
            INNER JOIN gs_role_data c ON b.role_id = c.role_id
            where a.admin_id=" . $request ["admin_id"];

        $res = mysqli::getAll($sql);

        $types = [];
        if (!empty($res)) {
            $types = array_column($res, "type");
        }
        if (!empty($types)) {
            foreach ($result as $key => &$value) {
                $value["is_have"] = !in_array($value["data_id"], $types) ? 0 : 1;
            }
        }
        return $this->json($result, 1);
    }
    /**
     * @OpDescription(whatFor="获取所有未删除用户(crm)",codeMonkey="")
     */
    public function adminalllistOp(){
        $model = new Model("admin");
        $list = $model->select();
        $admin_id_arr = array_column($list,'admin_id');
        $admin_ids = implode(',',$admin_id_arr);
        $reponse = CxtClient::generalizestaff_generalizestaff_generalizestaffbybindadminids(['admin_ids'=>$admin_ids]);
        if($reponse['code']!=1){
            return $this->json($reponse['message'], -1);
        }
        $admin_arr = Arr::arrayToArrayKey($reponse['data']['list'],'admin_id');
        foreach($list as $key=>&$value){
            if(!empty($value['img'])) {
                $value['img'] = Config::get('static_url') . $value['img'];
            }
            $value['study_code'] = $admin_arr[$value['admin_id']]['study_code'];
        }
        $res['list'] = $list;
        return $this->json($res,1);
    }

}