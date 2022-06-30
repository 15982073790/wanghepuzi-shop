<?php

namespace MrStock\System\Helper;

use MrstockCloud\Client\Request\Traits\MagicTrait;
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
    protected static $instances;
    public static function __callstatic($method, $param) {


        $routes = explode('-', $param[0]);
        if (count($routes) < 3) {
            throw new \Exception('站点-控制器-方法参数格式错误', -1);
        }
        $route_arr['site']               = $routes[0]; //站点
        $route_arr['v']                  = 'inneruse'; //分组
        $route_arr['c']                  = $routes[1]; //控制器
        $route_arr['a']                  = $routes[2]; //方法
        $param[1]['inneruse_secretkey'] = Config::get('inneruse_secretkey'); //方法
        $instances = new self(array_merge($route_arr, (array)$param[1])); //实例化合并赋值参数

        $res = $instances->$method();
        return $res;
    }
    /**
     * RpcRequest constructor.
     * @param array $data
     * @param bool $sdk
     */
    public function __construct($data = [])
    {
        Config::register();
        $this->request = Container::get('request');

        $this->bodyData = [];

        $this->parseParam($data);
    }

    /**
     * 解析请求参数
     * @param $data
     */
    private function parseParam($data)
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
    /**
     * 同步请求
     */
    protected function syncRequest()
    {
        if(empty($this->bodyData['api_version'])){$this->bodyData['api_version']="v1";}

        $worker_name=strtolower($this->bodyData['site']);
        $address = Config::get('rpc.'.$worker_name);
        //获取服务配置
        if (empty($address)) {
            throw new \Exception("所依赖的服务没配置:".$this->bodyData['site']);
        }

        RpcClient::config($address);
        $client = RpcClient::instance('server');
        $result = $client->trans($this->bodyData);
        //转换 非1 时的
        if ($result['code'] != 1) {
            $result = ['code' => $result['code'], 'message' => $result["message"]];
        }
        return $result;

    }

    /**
     * 异步请求
     */
    protected function asyncRequest()
    {
        if(empty($this->bodyData['api_version'])){$this->bodyData['api_version']="v1";}

        $worker_name=strtolower($this->bodyData['site']);
        $address = Config::get('rpc.'.$worker_name);
        //获取服务配置
        if (empty($address)) {
            throw new \Exception("所依赖的服务没配置:".$this->bodyData['site']);
        }

        RpcClient::config($address);
        $client = RpcClient::instance('server');

        $trans = RpcClient::ASYNC_SEND_PREFIX.'trans';
        $client->$trans($this->bodyData);

    }
    /**
     * 异步响应
     */
    protected function asyncResponse()
    {
        if(empty($this->bodyData['api_version'])){$this->bodyData['api_version']="v1";}

        $client = RpcClient::instance('server');
        $trans = RpcClient::ASYNC_RECV_PREFIX.'trans';
        $result = $client->$trans($this->bodyData);
        //转换 非1 时的
        if ($result['code'] != 1) {
            $result = ['code' => $result['code'], 'message' => $result["message"]];
        }
        return $result;

    }



}
