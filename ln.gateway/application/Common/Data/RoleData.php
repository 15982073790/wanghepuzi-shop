<?php

namespace Common\Data;

class RoleData
{
    const ADDDATA = [
        "role_name",
        "role_describe",
        "admin_id",
        "company_id",
        "institution_id",
        "type",
        "appcode",
        "itime"
    ];
    const UPDATA = [
        "role_name",
        "role_describe",
        "utime"

    ];
    const SELECTBASEDATA = [
        "role_id",
        "role_name",
        "role_describe",
        "admin_id",
        "itime",
        "datastatus"
    ];


    public static function getadd($request)
    {
        $result = [];
        $request["admin_id"] = $request["admin_id"] ? $request["admin_id"] : 0;
        $request["type"] = $request["type"] ? $request["type"] : 1;//1系统2机构层面3商家层面
        $request["company_id"] = $request["company_id"] ? $request["company_id"] : 0;
        $request["institution_id"] = $request["institution_id"] ? $request["institution_id"] : 0;
        $request["admin_id"] = $request["admin_id"] ? $request["admin_id"] : 0;
        $request["itime"] = time();
        foreach (self::ADDDATA as $key => $value) {
            # code...
            $result[$value] = $request[$value];
        }
        return $result;
    }

    public static function getup($request)
    {
        $result = [];
        $request["utime"] = time();

        foreach (self::UPDATA as $key => $value) {
            # code...
            $result[$value] = $request[$value];
        }
        return $result;
    }

    public static function getselectbasedata()
    {
        return self::SELECTBASEDATA;
    }


}