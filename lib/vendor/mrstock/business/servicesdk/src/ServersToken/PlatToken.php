<?php
namespace MrStock\Business\ServiceSdk\ServersToken;


use MrStock\Business\ServiceSdk\ServersToken\Secret;
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018-05-24
 * Time: 20:16
 */
class PlatToken
{
    private static $viLen = 16;

    private static $codeLen = 24;

    public static function tokenEncode($app_code, $app_key, $uid = 0)
    {
        $config = time() . ',' . $uid;
        
        $vi = substr($app_code, 0, self::$viLen);
        $se = new Secret($app_key, $vi);
        $secret = $se->encrypt($config);
        $secret = $app_code . $secret;
        return $secret;
    }

    public static function tokenDncode($secret = '', $app_key)
    {
        if (strlen($secret) > self::$codeLen) {
            $appCode = substr($secret, 0, self::$codeLen);
            $vi = substr($appCode, 0, self::$viLen);
            $secret = substr($secret, self::$codeLen, strlen($secret) - self::$codeLen);
            $se = new Secret($app_key, $vi);
            $config = $se->decrypt($secret);
            if ($config) {
                return explode(',', $config);
            }
        }
        return false;
    }
}