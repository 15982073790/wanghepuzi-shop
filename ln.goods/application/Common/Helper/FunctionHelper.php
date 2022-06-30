<?php

namespace Common\Helper;

use MrStock\Business\ServiceSdk\SecretMobile\SecretMobileRpc;
use MrStock\System\Helper\Config;
use MrStock\System\MJC\Container;
use MrStock\System\MJC\Facade\Log;
use MrStock\System\MJC\Log\LogLevel;
use MrStock\System\Helper\Arr;

class FunctionHelper
{
    /**
     * @param $adminInfo
     * @return string
     * 获取用户团队标识
     */
    public static function getAdminType($admin)
    {
        $result = "";
        if ($admin["department_id"] != 0) {
            $result = "company";
        } elseif ($admin["company_id"] != 0 && $admin["department_id"] == 0) {
            $result = "company";
        } elseif ($admin["institution_id"] != 0 && $admin["company_id"] == 0) {
            $result = "institution";
        } else {
            $result = "cloud";
        }

        return $result;
    }

    /**
     * 获取账号hash
     */
    public function get_hash()
    {
        $chars = 'abcdefghijklmnopqrstuvwxyz0123456789';
        $random = "";
        for ($i = 0; $i < 11; $i++) {
            # code...
            $random .= $chars[mt_rand(0, 35)];
        }
        $content = uniqid() . $random;
        return $content;
    }

    public function is_timestamp($timestamp)
    {

        if (strtotime(date('Y-m-d H:i:s', $timestamp)) . "" == $timestamp) {
            return true;
        } else {
            return false;
        }
    }

    public static function getsysconfig($hash_name, $data_id = "")
    {
        $result = "";
        if (!empty($hash_name)) {
            $hashtag = \MrstockCloud\Client\MrstockCloud::sysconfig()->inneruse()->v()
                ->Dictionary()
                ->getvalue(["hash_name" => $hash_name])
                ->request();

            if ($hashtag["code"] == 1 && !empty($hashtag["data"])) {
                $result = Arr:: arrayToArrayKey($hashtag["data"], "value");
                if (!empty($result)) {
                    $result = $result[$data_id]["score"];
                }
            }
        }
        return $result;
    }
}