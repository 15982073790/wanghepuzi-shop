<?php

namespace Common\Model;


use Common\Helper\FunctionHelper;
use MrStock\System\Helper\Tool;
use MrStock\System\Orm\Model;

class AdminModel extends Model
{
    public function __construct()
    {
        parent::__construct('admin');
    }

    /**
     * whs
     */
    public function selectbyadminids($admin_ids)
    {
        $res = $this->field('admin_id,username,truename')->where(["admin_id" => ["in", $admin_ids]])->limit(false)->select();

        return $res;
    }

    /**
     * where构建器
     */
    public function getwhere($request)
    {
        

        $request["lastlogintime_s"] = FunctionHelper::is_timestamp($request["lastlogintime_s"]) ? $request["lastlogintime_s"] : strtotime($request["lastlogintime_s"]);
        $request["lastlogintime_e"] = FunctionHelper::is_timestamp($request["lastlogintime_e"]) ? $request["lastlogintime_e"] : strtotime($request["lastlogintime_e"]);
        
        $like = ['truename_like','username_like'];
        $equal = [ "truename", "username","tel", "institution_id", "company_id", "department_id", "datastatus"];
        $gt = ["lastlogintime_s"];
        $lt = ["lastlogintime_e"];
        $notin = ["admin_ids_notin"];
        $result = [];
        foreach ($like as $key => $value) {
            if (trim($request[$value])) {
                $result[trim($value,'_like').'e'] = ['like', "%" . trim($request[$value]) . "%"];
            }
        }
        foreach ($equal as $key => $value) {

            if ($request[$value] || $request[$value] === '0') {
                $result[$value] = $request[$value];
            }
        }

        foreach ($gt as $key => $value) {
            if ($request[$value]) {
                $result[str_replace("_s", "", $value)] = ["gt", $request[$value]];
            }
        }
        foreach ($lt as $key => $value) {
            if ($request[$value]) {
                if (isset($result[str_replace("_e", "", $value)])) {
                    $result[str_replace("_e", "", $value)] = ["between", [$result[str_replace("_e", "", $value)][1], $request[$value]]];
                } else {
                    $result[str_replace("_e", "", $value)] = ["lt", $request[$value]];
                }

            }
        }
        

        $admin_ids = $this->getadminwhere($request);
        if (!empty($admin_ids)) {
            $result["admin_id"] = ["in", $admin_ids];
        }

        foreach ($notin as $key => $value) {
            if ($request[$value]) {
                $notinarr=explode(',', $request[$value]);

                if(!empty($admin_ids)){
                    $result["admin_id"] =["in",array_diff($admin_ids, $notinarr)];
                }else{
                    $result["admin_id"] = ['not in',  $notinarr];    
                }
                

            }
        }

        return $result;
    }

    /**
     * where构建器admin
     */
    private function getadminwhere($request)
    {

        $result = [];
        $role = ["table" => "admin_role", "param" => "role_id", "where" => ["role_id" => $request['role_id']]];
        $tag = ["table" => "admin_bussinessteam", "param" => "tag", "where" => ["tag" =>["in",explode(',', $request['tag'])] ]];
        $adminidfind = [$role, $tag];
        $admin_ids_arr = [];
        foreach ($adminidfind as $key => $value) {
            # code...
            if ($request[$value["param"]]) {

                $role = new Model($value["table"]);
                $res = $role->field("admin_id")->where($value["where"])->limit(false)->select();

                if (!empty($res)) {
                    $admin_ids_arr[] = array_column($res, "admin_id");
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

    public function checkpassword($admin_id, $password)
    {
        $result = 0;
        $res = $this->where(["admin_id" => $admin_id, "password" => $password])->find();
        if (!empty($res)) {
            $result = 1;
        }
        return $result;
    }
    //是否是超管
    public function issuper($admin_id)
    {
        $result = 0;
        $res = $this->where(["admin_id" => $admin_id])->find();
        if (!empty($res)&&$res["department_id"]==0) {
            $result = 1;
        }
        return $result;
    }

    public function getAdminInfo($admin_id)
    {
        return $this->table('admin')->where(['admin_id'=>$admin_id])->find();
    }
}