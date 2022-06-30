<?php

namespace Common\Data;

use Common\Model\AdminModel;
use MrStock\System\Orm\Model;

//数据默认值设定，场景下数据过滤
class AdminData
{
    public static $adddata = [
        "username",
        "password",
        "truename",
        "tel",
        "img",
        "itime",
        "mark"
    ];
    public static $updata = [
        "password",
        "truename",
        "tel",
        "utime",
        "username",
        "mark"
    ];

    public static function getadd($request)
    {
        $result = [];

        $request["password"] = md5($request["password"]);

        $request["itime"] = time();
        foreach (self::$adddata as $key => $value) {
            # code...
            $result[$value] = $request[$value];
        }
        return $result;
    }

    public static function getup($request)
    {
        $result = [];
        //是否正在改密码
        if ($request["password"]){
            $request["password"] = md5($request["password"]);
        }
        $request["utime"] = time();
        foreach (self::$updata as $key => $value) {
            # code...
            $result[$value] = $request[$value];
        }
        return $result;
    }

    public static function checkUserName($username)
    {
        $model = new Model();
        $res = $model->table('admin')->where(['username'=>$username])->find();
        if (empty($res)){
            return false; //不存在
        }
        return $res['admin_id']; //存在
    }

}