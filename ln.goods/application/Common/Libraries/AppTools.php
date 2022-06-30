<?php
namespace Common\Libraries;
use MrStock\System\Helper\Config;
use Common\Libraries\Gplattoken;
use Common\Libraries\Gsecretmobile;
/**
 * 应用站点工具类
 */
class AppTools
{
    /**
     * URL组装
     */
    public static function arrayToObject($data, $index, $is_group=0)
    {
        $return = [];
        foreach($data as $k=>$v){
            $group = $v[$index];
            if($is_group){
                $return[$group][] = $v;
            }else{
                $return[$group] = $v;
            }
        }
        return $return;
    }

    public static function getUrlContent($url)
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        $return = curl_exec($ch);
        curl_close($ch);
        return $return;
    }

    /*
     * curl post
     */
    public static function httpCurlPost($uri, $data,$headers=[], $timeout = 10)
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $uri);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        if ($headers) {
            $headers_n = [];
            foreach($headers as $k=>$v){
                $headers_n[] = $k.':'.$v;
            }
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers_n);
        }
        curl_setopt($ch, CURLOPT_TIMEOUT, $timeout);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        $return = curl_exec($ch);
        curl_close($ch);
        
        return $return;
    }

    /**
     * Content-Type: application/json请求
     */
    public function httpCurlPostJson($url,$data){
        $curl = curl_init();

        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
        if(!$data){
            return 'data is null';
        }
        curl_setopt($curl, CURLOPT_POST, 1);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
        curl_setopt($curl, CURLOPT_HEADER, 0);
        curl_setopt($curl, CURLOPT_HTTPHEADER,array(
            'Content-Type: application/json; charset=utf-8',
            'Content-Length:' . strlen($data),
            'Cache-Control: no-cache',
            'Pragma: no-cache'
        ));
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        $res = curl_exec($curl);
        $errorno = curl_errno($curl);
        if ($errorno) {
            return $errorno;
        }
        curl_close($curl);
        return $res;


    }

    /**
     * 程  序：iswap.php判断是否是通过手机访问
     * 版  本：Ver 1.0 beta
     * 修  改：奇迹方舟(imiku.com)
     * 最后更新：2010.11.4 22:56
     * @return boolean 是否是移动设备
     * 该程序可以任意传播和修改，但是请保留以上版权信息!
     */
    public static function isMobile()
    {
        // 如果有HTTP_X_WAP_PROFILE则一定是移动设备
        if (isset($_SERVER['HTTP_X_WAP_PROFILE'])) {
            return true;
        }
        //如果via信息含有wap则一定是移动设备,部分服务商会屏蔽该信息
        if (isset($_SERVER['HTTP_VIA'])) {
            //找不到为flase,否则为true
            return stristr($_SERVER['HTTP_VIA'], "wap") ? true : false;
        }
        //脑残法，判断手机发送的客户端标志,兼容性有待提高
        if (isset($_SERVER['HTTP_USER_AGENT'])) {
            $clientkeywords = [
                'nokia',
                'sony',
                'ericsson',
                'mot',
                'samsung',
                'htc',
                'sgh',
                'lg',
                'sharp',
                'sie-',
                'philips',
                'panasonic',
                'alcatel',
                'lenovo',
                'iphone',
                'ipod',
                'blackberry',
                'meizu',
                'android',
                'netfront',
                'symbian',
                'ucweb',
                'windowsce',
                'palm',
                'operamini',
                'operamobi',
                'openwave',
                'nexusone',
                'cldc',
                'midp',
                'wap',
                'mobile',
                'MicroMessenger',
            ];
            // 从HTTP_USER_AGENT中查找手机浏览器的关键字
            if (preg_match("/(" . implode('|', $clientkeywords) . ")/i", strtolower($_SERVER['HTTP_USER_AGENT']))) {
                return true;
            }
        }
        //协议法，因为有可能不准确，放到最后判断
        if (isset($_SERVER['HTTP_ACCEPT'])) {
            // 如果只支持wml并且不支持html那一定是移动设备
            // 如果支持wml和html但是wml在html之前则是移动设备
            if ((strpos($_SERVER['HTTP_ACCEPT'], 'vnd.wap.wml') !== false) &&
                (strpos($_SERVER['HTTP_ACCEPT'], 'text/html') === false ||
                 (strpos($_SERVER['HTTP_ACCEPT'], 'vnd.wap.wml') < strpos($_SERVER['HTTP_ACCEPT'], 'text/html')))) {
                return true;
            }
        }
        
        return false;
    }

    //是否来自微信浏览器
    public static function isFromWeixin()
    {
        return isset($_SERVER['HTTP_USER_AGENT']) && strpos($_SERVER['HTTP_USER_AGENT'], 'MicroMessenger') !== false ?
            true : false;
    }

    /**
     * 如果财学堂上传有头像返回查询头像,否则返回原头像
     *
     * @param [type] $memberId
     * @param [type] $avatar
     * @return void
     */
    public static function getCxtAvatar($memberId) {
        $avatar = self::getAvatar($memberId);
        if($avatar && self::remoteFileExist($avatar)){
            return $avatar;
        }

        return Config::get('cxt_app_default_img_120'); //默认头像;
    }

    /** 
     * 判断远程文件是否存在的方法
     */
    public static function remoteFileExist($file)
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL,$file);
        curl_setopt($ch, CURLOPT_NOBODY, 1); // 不下载
        curl_setopt($ch, CURLOPT_FAILONERROR, 1);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_TIMEOUT, 1);

        if(curl_exec($ch)!==false)
            return true;
        else
            return false;
    }


    /**
     * 获取用户头像链接
     * @param int $member_id
     * @throws Exception
     */
    public static function getAvatar($member_id)
    {
        if ($member_id > 0) {
            return Config::get("static_file_url") . Config::get('avater_file_path') . "/" . $member_id . ".jpg";
        }
        return '';
    }



    //装换数组主键
    public static  function array_to_array_key($arr, $field, $group = 0)
    {
        $array = [];
        if (empty($arr)) {
            return $array;
        }
        if ($group == 0) {

            foreach ($arr as $v) {
                $v = (is_object($v))?(array)$v:$v;
                if (array_key_exists($field, $v)) {
                    $array[$v[$field]] = $v;
                }
            }
        } else {
            foreach ($arr as $v) {
                $v = (is_object($v))?(array)$v:$v;
                if (array_key_exists($field, $v)) {
                    $array[$v[$field]][] = $v;
                }
            }
        }
        
        return $array;
    }

    /**
     * 加密
     */
    public static function getDataSign($data)
    {
        ksort($data);
        $str = '';
        foreach($data as $k=>$v){
            $str .= $k.'='.$v;
        }
        return md5(Config::get('cxt_api_key').$str);
    }

    /**
     * @param $data
     * @param $countNum
     * @param $pageSize
     * @return array
     * 获取分页数据
     */
    public static function getPageData($data, $countNum, $pageSize)
    {
        return [
            'page_count'=>ceil($countNum / $pageSize),
            'total_num'=>$countNum,
            'list'=>$data
        ];
    }


    /**
     * @param $data
     * @return array|bool|string
     */
     public static function data_base64_decode($data){
        if(is_array($data)){
            foreach($data as $k=>$v){
                $data[$k] = self::data_base64_decode($v);
            }
            return $data;
        }else{
            $data = (string)$data;
            return base64_decode($data);
        }
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
    public static function morePage($list,$total_num,$currage,$pagesize){
        $hasmore=false;
        $list = self::_unsetNull($list);
        $total_page=(string)ceil($total_num/$pagesize);//总页数
        if($currage<$total_page){
            $hasmore=true;
        }
        return ['hasmore'=>$hasmore,'total_page'=>$total_page,'total_num'=>$total_num,'list'=>$list];
    }
    /**
     * 后台分页使用
     */
    public static function mmorePage($list,$total_num,$curpage,$pagesize){
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
     * 后台分页使用
     */
    public static function mpage($arr,$curpage,$pagesize)
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

    /**
     * @param $array
     * @param $key
     * @return array
     * @comment 根据二维数组的字段去重
     */
    function unique_multidim_array($array, $key) {
        $temp_array = array();
        $i = 0;
        $key_array = array();

        foreach($array as $val) {
            if (!in_array($val[$key], $key_array)) {
                $key_array[$i] = $val[$key];
                $temp_array[$i] = $val;
            }
            $i++;
        }
        return $temp_array;
    }
    /*单个手机号加密*/
    static public function gsecret_mobile_simple($mobile=''){
        $app_code= Config::get('token_encode')['service_app_code'];
        $app_key= Config::get('token_encode')['service_app_key'];
        $token=  Gplattoken::tokenEncode($app_code, $app_key);
        $mobiles=array($mobile);
        $mobileinfo= Gsecretmobile::encrypt($token, $mobiles);
        return $mobileinfo["$mobile"];
    }
    /*单个多个手机解密*/
    static public function decrypt_mobile_simple($mobile=[]){
        $app_code= Config::get('token_encode')['service_app_code'];
        $app_key= Config::get('token_encode')['service_app_key'];
        $token=  Gplattoken::tokenEncode($app_code, $app_key);
        $mobileinfo= Gsecretmobile::decrypt($token, $mobile);
        return  $mobileinfo  ;
    }
    /*多个手机号加密*/
    static public function gsecret_mobile($mobiles=[]){
        $app_code= config('system')['service_app_code'];
        $app_key= config('system')['service_app_key'];
        $token=  Gplattoken::tokenEncode($app_code, $app_key);
        $mobileinfo= Gsecretmobile::encrypt($token, $mobiles);
        return $mobileinfo;
    }

    /**
     * 写入base64图片到指定目录
     */
    public function writeimg($data, $file_path = '',$level_dir_name)
    {
        $file_name = (int)(microtime(true)*1000000) . '.' . 'jpg';
        $level_dir = '/'.$level_dir_name; //uploads下级目录名
        $static_path = $level_dir.'/'.$file_name; //用来存储的地址
        $dir = $file_path . $level_dir; //检测的目录
        if(!file_exists($dir)){
            //检查是否有该文件夹，如果没有就创建，并给予最高权限
            mkdir($dir, 0777,true);
        }
        $file_name_path = $dir.'/'.$file_name; //写入的目录文件
        if (!file_put_contents($file_name_path, $data)) {
            return ['message'=>'写入失败','code'=>-1];
        }
        return ['path'=>$static_path,'code'=>1];

    }
}

























