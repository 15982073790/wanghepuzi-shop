<?php
/**
 * 手机加解密 sdk  http调用
 */
namespace MrStock\Business\ServiceSdk\SecretMobile;

use MrStock\System\MJC\Container;
use MrStock\System\Helper\HttpRequest;

class SecretMobileHttp implements AbstractMobileSecret
{
    private static $baseUrl = 'http://secretmobile.guxiansheng.cn'; //手机号加密地址
    
    public static function encrypt($servicestoken, $mobiles = [])
    {
        if (! empty($mobiles) && is_array($mobiles)) {
            
            $mobiles = json_encode($mobiles);
            $mobiles = base64_encode($mobiles);
            $data = array();
            $data['c'] = 'secretmobile';
            $data['a'] = 'encrypt';
            $data['mobiles'] = $mobiles;
            $data['servicestoken'] = $servicestoken;
            
            $url = self::$baseUrl;
            $rs = HttpRequest::query($url, $data, true);
         
            if (isset($rs['data'])) {
                $rs = $rs['data'];
                if (is_array($rs)) {
                    return $rs;
                }
            }
            Container::get('log')->record('secretmobile_encrypt_err：'.$url.'?'.http_build_query($data));
        }

        return false;
    }

    public static function decrypt($servicestoken, $secrets = [])
    {
        if (! empty($secrets)) {
            
            $secrets = json_encode($secrets);
            $secrets = base64_encode($secrets);
            
            $data = array();
            $data['c'] = 'secretmobile';
            $data['a'] = 'decrypt';
            $data['secrets'] = $secrets;
            $data['servicestoken'] = $servicestoken;
            
            $url = self::$baseUrl;
            $rs = HttpRequest::query($url, $data, true);
            if (isset($rs['data'])) {
                $rs = $rs['data'];
                if (is_array($rs)) {
                    return $rs;
                }
            }
            Container::get('log')->record('secretmobile_decrypt_err：'.$url.'?'.http_build_query($data));
        }
        return false;
    }
}