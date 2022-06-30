<?php
/**
 * Created by PhpStorm.
 * User: 10555
 * Date: 2019/3/12
 * Time: 18:29
 */
namespace Common\Logic;
//use OSS\OssClient;
//use OSS\Core\OssException;
use AlibabaCloud\Client\AlibabaCloud;
use MrStock\System\Helper\Config;

class SaveByOssLogic
{
    private         $readPolicy= null;
    private         $writePolicy = null;

    private         $accessKeyId = null;
    private         $accessKeySecret =  null;
    private         $endpoint =  null;    // 服务器Regin
    private         $ossClient = null;
    private         $bucket =  null;                            //主目录
    public          $site = null;
    private         $regionId =  null;
    private         $readArn =  null;
    private         $writeArn =  null;
//    返回值.
    private         $responseRequestId = null;
    private         $responseAssumedRoleId = null;
    private         $responseArn = null;
    private         $responseAccessKeySecret = null;
    private         $responseAccessKeyId = null;
    private         $responseExpiration = null;
    private         $responseSecurityToken = null;

    /**
     * 连接初始化. todo 每次会话都要获取token还是把token存数据库?.需要异常捕获
     * SaveByOss constructor.
     */
    public function __construct($site)
    {
        $this->readPolicy = Config::get('readPolicy');
        $this->writePolicy =  Config::get('writePolicy');
        $this->accessKeyId= Config::get('huihui_accessKeyId');
        $this->accessKeySecret =  Config::get('huihui_accessKeySecret');
        $this->endpoint =  Config::get('huihui_endpoint');
        $this->bucket= Config::get('huihui_bucket');
        $this->regionId= Config::get('huihui_regionId');
        $this->readArn= Config::get('huihui_readArn');
        $this->writeArn= Config::get('huihui_writeArn');
        $this->site= $site;
        AlibabaCloud::accessKeyClient($this->accessKeyId, $this->accessKeySecret)->regionId($this->regionId)->asGlobalClient();
        $response = AlibabaCloud::sts()->v20150401()->AssumeRole()->setRoleSessionName($site)->setRoleArn($this->writeArn)->setPolicy($this->writePolicy)->setDurationSeconds(3600)->options(['verify'=>false])->request();
        $response = $response->toArray();
        $this->responseRequestId = $response["RequestId"];
        $this->responseAssumedRoleId = $response["AssumedRoleUser"]["AssumedRoleId"];
        $this->responseArn =  $response["AssumedRoleUser"]["Arn"];
        $this->responseAccessKeySecret = $response["Credentials"]["AccessKeySecret"];
        $this->responseAccessKeyId = $response["Credentials"]["AccessKeyId"];
        $this->responseExpiration = $response["Credentials"]["Expiration"];
        $this->responseSecurityToken = $response["Credentials"]["SecurityToken"];
    }

    /**
     * 获取url下载token
     */
    public function getDownloadToken(){
//        $this->ossClient =  new OssClient($this->responseAccessKeyId, $this->responseAccessKeySecret, $this->endpoint, false, $this->responseSecurityToken);

    }

    /**
     * @Description 返回token给外部调用
     * @return array
     */
    public function getToken(){
        return [
            "AccessKeySecret"=>$this->responseAccessKeySecret
            ,"Expiration"=>$this->responseExpiration
            ,"SecurityToken"=>$this->responseSecurityToken
            ,"AccessKeyId"=>$this->responseAccessKeyId
        ];
    }

    private function getKeyBySts(){}

    /**
     * 上传单个文件
     */
    public function putFile($file){
        $result = $this->ossClient->uploadFile($this->bucket,$this->createRemoteSaveName($file), $file["tmp_name"]);
        if($result && $result["info"] && $result["info"]["http_code"]==200){
            return $result["info"]["url"];
        }else{
            return false;
        }
    }

    public function getFile($fileUrl){
        $content = $this->ossClient->getObject($this->bucket, $fileUrl);
    }

    /**
     * 上传多个文件
     */
    public function putFiles(){}



    /**
     * @Description 生成oss的objectName
     * @param $file
     * @return string
     */
    public function createRemoteSaveName($file){
        $tmpName = sprintf('%010d', time() - 946656000) . sprintf('%03d', microtime() * 1000) . sprintf('%04d', mt_rand(0, 9999));
        $date = date('Y_m_d_H_i_s_') ;
        $tmpExt = explode(".",$file['name']);
        $tmpExt = $tmpExt[count($tmpExt) - 1];
        return $date.$tmpName.".".strtolower($tmpExt);
    }




}