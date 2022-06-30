<?php

namespace Common\Data;
//数据默认值设定，场景下数据过滤
class AdminbussinessteamData
{

    const ADDDATA = [
        "admin_id",
        "tag",
        "privilegecode",
        "zhiyebianhao",
        "zuoxihao",
        "intro",
        "gangwei",
        "touxian",
        "touxianimg",
        "loudong",
        "calling_card_img",
        "sersonal_signature",
        "doctor_info",
    ];
    const UPDATA = [
        "gangwei",
        "zhiyebianhao",
        "privilegecode",
        "zuoxihao",
        "intro",
        "touxian",
        "touxianimg",
        "loudong",
        "calling_card_img",
        "tag",
        "sersonal_signature",
        "doctor_info",

    ];

    public static function getadd($request)
    {

        $result = [];

        foreach (self::ADDDATA as $key => $value) {
            # code...
            if (isset($request[$value])){
                $result[$value] = $request[$value];
            }
        }
        return $result;
    }

    public static function getup($request)
    {

        $result = [];

        foreach (self::UPDATA as $key => $value) {
            # code...
            $result[$value] = $request[$value];
        }
        return $result;
    }

}