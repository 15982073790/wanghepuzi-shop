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
    /**
     * @param $list
     * @param $total_num
     * @param $currage
     * @param $pagesize
     * @return array
     * @comment 数据库分页该函数判断组合
     * @
     */
    public static function morePage($list,$total_num,$curpage,$pagesize){
        $hasmore=false;
        $list = self::_unsetNull($list);
        $total_page=(string)ceil($total_num/$pagesize);//总页数
        if($curpage<$total_page){
            $hasmore=true;
        }
        return [
            'hasmore'=>$hasmore,
            'pageNo'=>$curpage,
            'pageSize'=>$pagesize,
            'totalPage'=>$total_page,
            'totalCount'=>$total_num,
            'list'=>$list
        ];
    }
    //与后台管理系统框架分页一致
    //"pageSize": 10, 一页多少条
    //"pageNo": 0,当前第几页
    //"totalPage": 6,总共多少页
    //"totalCount": 57总共多少条记录
    //成功
    /**
     * @param $arr
     * @param $currage
     * @param $pagesize
     * @return array
     * 取出所有数据再分页
     */
    public static function page($arr,$curpage,$pagesize)
    {
        $hasmore=false;
        $total_num=count($arr);//总条数
        $total_page=ceil($total_num/$pagesize);//总页数
        $list=array_slice($arr,($curpage-1)*$pagesize,$pagesize);
        if($curpage<$total_page){
            $hasmore=true;
        }

        return [
            'hasmore'=>$hasmore,
            'pageNo'=>$curpage,
            'pageSize'=>$pagesize,
            'totalPage'=>$total_page,
            'totalCount'=>$total_num,
            'list'=>$list
        ];

    }
    /**
     * @param $arr
     * @return array|string
     * @comment 递归方式把数组或字符串 null转换为空''字符串
     * @comment 
     */
    public static function _unsetNull($arr){
        if($arr !== null){
            if(is_array($arr)){
                if(!empty($arr)){
                    foreach($arr as $key => $value){
                        if($value === null){
                            $arr[$key] = '';
                        }else{
                            $arr[$key] = static::_unsetNull($value);      //递归再去执行
                        }
                    }
                }else{
                    $arr = [];
                }
            }else{
                if($arr === null){
                    $arr = '';
                }         //注意三个等号
            }
        }else{
            $arr = '';
        }
        return $arr;
    }
}