<?php

namespace MrstockCloud\Client\Request;

use MrStock\System\Helper\HttpRequest;
use MrStock\System\MJC\Facade\Log;
use MrStock\System\MJC\Log\LogLevel;
use MrstockCloud\Client\MrstockCloud;
use MrstockCloud\Client\Request\Traits\MagicTrait;
use MrStock\System\Helper\Config;
use MrStock\System\MJC\Container;

/**
 * Class RpcRequest
 * @package MrstockCloud\Client\Request
 */
class RpcRequest
{
    use MagicTrait;

    protected $serviceName;
    protected $moduleName;
    protected $versionName;
    protected $controlName;
    protected $actionName;

    protected $bodyData;

    protected $request;

    /**
     * RpcRequest constructor.
     * @param array $data
     * @param bool $sdk
     */
    public function __construct($data = [], $useSdk = true)
    {
        Config::register();
        $this->request = Container::get('request');

        $this->bodyData = [];
        $this->parseParam($data);
        if ($useSdk) {
            $this->parseSdk();
        }
    }

    /**
     * 解析请求参数
     * @param $data
     */
    protected function parseParam($data)
    {
        $this->bodyData = $data;
        if (is_array($this->bodyData)) {
            foreach ($this->bodyData as $key => $item) {
                if (is_array($item)) {
                    $this->bodyData[$key] = json_encode($item, JSON_UNESCAPED_UNICODE);
                }
            }
        }
    }


    /** 服务发起请求
     * @param null $data 手动指定参数(不通过反射获取)
     * @return mixed
     * @throws \Exception
     */
    public function request()
    {
        try {
            return $this->rpcRequest();
            /*if($this->bodyData['v']=="inneruse"){
                return $this->inneruserpcRequest();
            }else{
                return $this->rpcRequest();
            }*/

        } catch (\Exception $e) {

            //记录日志 错误信息不返回
            Log::write($e->getMessage(), LogLevel::RPCDEBUG);

            $message = $this->request->isdebug ? $e->getMessage() : "RPCERROR";
            throw  new \Exception($message, $e->getCode());
        }
    }


    public function httpRequest()
    {
        try {
            $url = "http://".$this->bodyData['site'].".api.caixuetang.cn/index.php";
            $this->bodyData['check_c'] = $this->request->c;
            $this->bodyData['check_a'] = $this->request->a;
            $this->bodyData['check_v'] = $this->request->v;
            $this->bodyData['check_site'] = $this->request->site;
            $this->bodyData['inneruse_secretkey'] = Config::get("rpc_inneruse_secretkey");
            return HttpRequest::query($url,$this->bodyData,1);
        } catch (\Exception $e) {

            $message = $this->request->isdebug ? $e->getMessage() : "RPCERROR";
            throw  new \Exception($message, $e->getCode());
        }
    }

    /**
     * 发起index 请求
     * @return mixed
     * @throws \Exception
     */
    protected function indexRequest()
    {
        $site=$this->bodyData['site'];
        $message=$this->bodyData;
        $indexurl='/data/rpcwww/'.$site.'.service/public/index.php'; 
        if (! include_once ($indexurl)) exit($site.'_index.php isn\'t exists!');
     
            
        $_REQUEST = $_POST = $_GET = $message;
        
   
        try {

            $response = (new \MrStock\System\MJC\App())->run();
            $data = $response->getContent();
        } catch (\Throwable $ex){
            (new \MrStock\System\Orm\Model())->closeTransaction();
            $response= \MrStock\System\Helper\Output::response($ex->getMessage(), $ex->getCode(), 200);
            $data = $response->getContent();
        }
        
        if(isset($message['callback']) && $message['callback'])
        {
            $data=str_replace($message['callback'], "", $data);
            $data=str_replace("(", "", $data);
            $data=str_replace(");", "", $data);
        }
        $result=json_decode($data);
        if(empty($result)){
            \MrStock\System\MJC\Facade\Log::write(print_r($_REQUEST,true)." indexrs:".$data, RPCERR);
        }
        if ($result['code'] != 1) {
            $result = ['code' => $result['code'], 'message' => $result["message"], 'data' => $result["message"]];
        }
        return $result;


    
    }
     /**
     * 发起rpc 请求
     * @return mixed
     * @throws \Exception
     */
    protected function inneruserpcRequest()
    {
        

        if(empty($this->bodyData['api_version'])){$this->bodyData['api_version']="v1";}
        $tmp=["v2","v1"];
        foreach ($tmp as $key => $value) {
            # code...
            $vendor_worker_name=strtolower($this->bodyData['site'].'-'.$this->bodyData['c'].'-'.$this->bodyData['a'].'-'.$this->bodyData['v'].'-'.$value);
            $address = Config::get($vendor_worker_name);
            if(!empty($address)){
                break;
            }
        }
        
        //获取服务配置
        if (empty($address)) {
            throw new \Exception("所依赖的服务没配置:".$this->bodyData['site'].":".$vendor_worker_name, -__LINE__);
        }

        RpcClient::config($address);
        $client = RpcClient::instance('server');

        Log::write(http_build_query($this->bodyData));
     
        $result = $client->trans($this->bodyData);

        //转换 非1 时的
        if ($result['code'] != 1) {
            $result = ['code' => $result['code'], 'message' => $result["message"], 'data' => $result["message"]];
        }
        return $result;
    }
    /**
     * 发起rpc 请求
     * @return mixed
     * @throws \Exception
     */
    protected function rpcRequest()
    {
        
		
        $address = Config::get('rpc.' . $this->bodyData['site']);
      
		        //获取服务配置
        if (empty($address)) {
            $vendor_worker_name="";
            throw new \Exception("所依赖的服务没配置:".$this->bodyData['site'].":".$vendor_worker_name, -__LINE__);
        }

        RpcClient::config($address);
        $client = RpcClient::instance('server');

        Log::write(http_build_query($this->bodyData));
     
        $result = $client->trans($this->bodyData);

        //转换 非1 时的
        if ($result['code'] != 1) {
            $result = ['code' => $result['code'], 'message' => $result["message"], 'data' => $result["message"]];
        }
        return $result;
    }

    /**
     * 通过 sdk 调用 组装请求参数
     */
    protected function parseSdk()
    {
        $className = get_class($this);

        $classNameArray = explode("\\", $className);
        $this->serviceName = strtolower($classNameArray[1]);
        $this->moduleName = strtolower($classNameArray[2]);
        $this->versionName = $classNameArray[3] == "V" ? "" : $classNameArray[3];
        $this->controlName = $classNameArray[4];
        $this->actionName = $classNameArray[5];

        $this->bodyData['site'] = $this->serviceName;
        $this->bodyData['v'] = $this->moduleName;
        $this->bodyData['serviceversion'] = $this->versionName;
        $this->bodyData['c'] = $this->controlName;
        $this->bodyData['a'] = $this->actionName;

        if (MrstockCloud::$APPCODE) {
            $this->bodyData['appcode'] = MrstockCloud::$APPCODE;
        }
        if (MrstockCloud::$SECRETKEY) {
            $this->bodyData['inneruse_secretkey'] = MrstockCloud::$SECRETKEY;
        }
        $this->bodyData['refer_site'] = $this->request['site'];
        $this->bodyData['isdebug'] =  $this->request['isdebug'];
        $this->bodyData['rpc_msg_id'] =  $this->request['rpc_msg_id'];
        $this->bodyData['rpc_msg_time'] =  $this->request['rpc_msg_time'];

        $this->bodyData["refer_api"] = http_build_query($this->request->param);
    }

    /**
     * Magic method for set or get request parameters.
     *
     * @param string $name
     * @param mixed $arguments
     *
     * @return $this
     */
    public function __call($name, $arguments)
    {
        if (\strpos($name, 'get') !== false) {
            $parameterName = $this->propertyNameByMethodName($name);
            return $this->__get($parameterName);
        }

        if (\strpos($name, 'with') !== false) {
            $parameterName = $this->propertyNameByMethodName($name, 4);
            $this->__set($parameterName, $arguments[0]);
            $this->bodyData[$parameterName] = $arguments[0];
        }

        return $this;
    }
}
