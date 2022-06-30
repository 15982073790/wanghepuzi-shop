<?php
/**
 * 手机或者证件号加解密 sdk  rpc调用
 */
namespace MrStock\Business\ServiceSdk\SecretMobile;


use MrStock\System\Helper\Arr;
class SecretMobileRpc  
{
    
    
    public  static function encrypt($mobiles=[])
    {

        try{
            if (! empty($mobiles) && is_array($mobiles)) {
           return \MrstockCloud\Client\MrstockCloud::secret()->inneruse()->v()
                              ->Secretmobile()
                              ->encrypt(["mobiles"=>base64_encode(json_encode($mobiles))])
                              ->request();
           
            }else{
                throw new \Exception("mobiles 格式错误",-1);
            }           
        }catch(\Exception $e){
            return ['code'=>$e->getCode(), 'message'=>$e->getMessage()];
        }

    }

    public static function decrypt($secrets=[])
    {
        try{
           if (! empty($secrets) && is_array($secrets)) {
            return \MrstockCloud\Client\MrstockCloud::secret()->inneruse()->v()
                              ->Secretmobile()
                              ->decrypt(["secrets"=>base64_encode(json_encode($secrets))])
                              ->request();
      
            }else{
                throw new \Exception("secrets 格式错误",-1);
            }      
        }catch(\Exception $e){
            return ['code'=>$e->getCode(), 'message'=>$e->getMessage()];
        }

    }
    /**
     * @access public 
     * @deprecated 处理后台账号的手机或者证件号解密，有权限控制
     * @param arr $secrets 加密后的字符串组成的数组
     * @param int $admin_id 后台账号id
     * @param int $type 处理解密的类型 1为手机号 2为证件号
     * @return arr
     * @throws code=1标识解密成功
     */
    public static function decrypt_auth_adminid($secrets=[],$admin_id=0,$type=1)
    {
        try{
           if(empty($admin_id)){
                throw new \Exception("admin_id 格式错误",-1);
           }
           $res=\MrstockCloud\Client\MrstockCloud::gateway()->inneruse()->v()
                              ->Admin()
                              ->databyadminid(["admin_id"=>$admin_id])
                              ->request();

           if($res["code"]!=1){
                throw new \Exception("gateway_Admin_databyadminid err",-1);
           }
           if($res["data"]){
              $arr=Arr::arrayToArrayKey($res["data"], "data_id");
              switch ($type) {
                case 1:
                  # code...
                if($arr[1]["is_have"]==0){
                   throw new \Exception("你无权解密",-1);
                }
                  break;
                case 2:
                  # code...
                if($arr[2]["is_have"]==0){
                   throw new \Exception("你无权解密",-1);
                }
                  break;
                default:
                  # code...
                  break;
              }
              
           }
           if (! empty($secrets) && is_array($secrets)) {      
           return \MrstockCloud\Client\MrstockCloud::secret()->inneruse()->v()
                              ->Secretmobile()
                              ->decrypt(["secrets"=>base64_encode(json_encode($secrets))])
                              ->request();      

            }else{
                 throw new \Exception("secrets 格式错误",-1);
            }
        }catch(\Exception $e){
            return ['code'=>$e->getCode(), 'message'=>$e->getMessage()];
        }

        
        return false;
    }
}