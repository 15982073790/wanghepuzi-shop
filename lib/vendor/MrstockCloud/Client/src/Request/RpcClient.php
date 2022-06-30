<?php

namespace MrstockCloud\Client\Request;

use MrStock\System\MJC\Container;
use MrStock\System\MJC\Facade\Debug;

/**
 * This file is part of workerman.
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the MIT-LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @author walkor<walkor@workerman.net>
 * @copyright walkor<walkor@workerman.net>
 * @link http://www.workerman.net/
 * @license http://www.opensource.org/licenses/mit-license.php MIT License
 */

if (!class_exists('JsonProtocol')) create_JsonProtocol();

/**
 *
 *  RpcClient Rpc客户端
 *
 *
 *  示例
 *  // 服务端列表
 * $address_array = array(
 * 'tcp://127.0.0.1:2015',
 * 'tcp://127.0.0.1:2015'
 * );
 * // 配置服务端列表
 * RpcClient::config($address_array);
 *
 * $uid = 567;
 * $user_client = RpcClient::instance('User');
 * // ==同步调用==
 * $ret_sync = $user_client->getInfoByUid($uid);
 *
 * // ==异步调用==
 * // 异步发送数据
 * $user_client->asend_getInfoByUid($uid);
 * $user_client->asend_getEmail($uid);
 *
 * 这里是其它的业务代码
 * ..............................................
 *
 * // 异步接收数据
 * $ret_async1 = $user_client->arecv_getEmail($uid);
 * $ret_async2 = $user_client->arecv_getInfoByUid($uid);
 *
 * @author walkor <worker-man@qq.com>
 */
class RpcClient
{
    /**
     * 同步接收 重试次数
     */
    const TRY_COUNT = 1;


    /**
     * 发送数据和接收数据的超时时间  单位S
     * @var integer
     */
    const TIME_OUT = 5;

    /**
     * 异步调用发送数据前缀
     * @var string
     */
    const ASYNC_SEND_PREFIX = 'asend_';

    /**
     * 异步调用接收数据
     * @var string
     */
    const ASYNC_RECV_PREFIX = 'arecv_';

    /**
     * sokect 状态 发送中
     */
    const STATE_SEND = 'sending';

    /**
     * sokect 状态 接收中
     */
    const STATE_RECV = 'recving';

    /**
     * sokect 状态 空闲
     */
    const STATE_IDLE = 'idle';

    /**
     * 服务端地址
     * @var array
     */
    protected static $addressArray = array();

    /**
     * 异步调用实例
     * @var string
     */
    protected static $asyncInstances = array();

    /**
     * 同步调用实例
     * @var string
     */
    protected static $instances = array();

    /**
     * 到服务端的socket连接
     * @var resource
     */
    protected $connection = null;

    /**
     * 实例的服务名
     * @var string
     */
    protected $serviceName = '';

    protected $curSendData;
    protected $curAddress;
    protected $curResult;
    protected $curStartTime;
    protected $curEndTime;
    protected $curTotal;
    protected $request;
    protected $debug;

    public static $connectionPool;

    /**
     * 设置/获取服务端地址
     * @param array $address_array
     */
    public static function config($address_array = array())
    {
        if (!empty($address_array)) {
            self::$addressArray = $address_array;
        }
        return self::$addressArray;
    }

    /**
     * 获取一个实例
     * @param string $service_name
     * @return instance of RpcClient
     */
    public static function instance($service_name)
    {
        //不持久化对象
        return new self($service_name);
        if (!isset(self::$instances[$service_name])) {
            self::$instances[$service_name] = new self($service_name);
        }
        return self::$instances[$service_name];
    }

    /**
     * 构造函数
     * @param string $service_name
     */
    public function __construct($service_name)
    {
        $this->request = Container::get('request');
        $this->debug = Container::get('debug');
        $this->serviceName = $service_name;
    }

    /**
     * 调用
     * @param string $method
     * @param array $arguments
     * @throws Exception
     * @return
     */
    public function __call($method, $arguments)
    {
        // 判断是否是异步发送
        if (0 === strpos($method, self::ASYNC_SEND_PREFIX)) {
            $real_method = substr($method, strlen(self::ASYNC_SEND_PREFIX));
            $instance_key = $real_method . serialize($arguments);
            if (isset(self::$asyncInstances[$instance_key])) {
                throw new \Exception($this->serviceName . "->$method(" . implode(',', $arguments) . ") have already been called", -__LINE__);
            }
            self::$asyncInstances[$instance_key] = new self($this->serviceName);
            return self::$asyncInstances[$instance_key]->sendData($real_method, $arguments);
        }
        // 如果是异步接受数据
        if (0 === strpos($method, self::ASYNC_RECV_PREFIX)) {
            $real_method = substr($method, strlen(self::ASYNC_RECV_PREFIX));
            $instance_key = $real_method . serialize($arguments);
            if (!isset(self::$asyncInstances[$instance_key])) {
                throw new \Exception($this->serviceName . "->asend_$real_method(" . implode(',', $arguments) . ") have not been called", -__LINE__);
            }
            $tmp = self::$asyncInstances[$instance_key];
            unset(self::$asyncInstances[$instance_key]);
            return $tmp->recvData();
        }
        // 同步发送接收
        return $this->sync($method, $arguments);
    }


    /**
     * 同步发送和接收
     * @param $method
     * @param $arguments
     * @param int $tryInt 重试次数
     * @return mixed
     * @throws \Exception
     */
    protected function sync($method, $arguments, $tryInt = 0)
    {
        $this->sendData($method, $arguments);
        return $this->recvData($method, $arguments, $tryInt);
    }


    /**
     * 发送数据给服务端
     * @param string $method
     * @param array $arguments
     */
    public function sendData($method, $arguments)
    {
        $this->curStartTime = microtime(true);
        $this->curSendData = JsonProtocol::encode(array('class' => $this->serviceName, 'method' => $method, 'param_array' => $arguments,));

        $this->setConnection();

        self::$connectionPool[$this->curAddress]['state'] = self::STATE_SEND;

       
        if (fwrite($this->connection, $this->curSendData) !== strlen($this->curSendData)) {

            $this->closeConnection();
            //重试一次
            $this->setConnection();
            if (fwrite($this->connection, $this->curSendData) !== strlen($this->curSendData)) {
                throw new \Exception('Can not send data', -__LINE__);
            }
        }
    }


    /**
     * 从服务端接收数据
     * @param null $method
     * @param null $arguments
     * @param int $tryInt 重试次数
     * @return mixed
     * @throws \Exception
     */
    public function recvData($method = null, $arguments = null, $tryInt = 0)
    {
        self::$connectionPool[$this->curAddress]['state'] = self::STATE_RECV;

        $ret = fgets($this->connection);

        $this->curResult = $ret;
        $this->curEndTime = microtime(true);
        $runTime = round($this->curEndTime - $this->curStartTime, 6) * 1000;
  
        if (!$ret) {
          
            $info = stream_get_meta_data($this->connection);
            $timed_out=0;
            if ($info['timed_out']) {
                $timed_out=1;
            }
            $this->closeConnection();
           
            //没超时 则重试一次
            if (!$info['timed_out'] && $method && $tryInt < self::TRY_COUNT) {
                $tryInt++;
                return $this->sync($method, $arguments, $tryInt);
            }
            throw new \Exception("recvData empty --- address: " . $this->curAddress . ":" . $this->curTotal . " ---senddata: " . $this->curSendData . " ---runtime: " . $runTime . "---tryint:" . $tryInt. "---timed_out:" . $timed_out, -__LINE__);
        }

        $result = JsonProtocol::decode($ret);

        if (!isset($result["code"])) {
            $this->closeConnection();
            throw new \Exception("recvData code not set --- address: " . $this->curAddress . ":" . $this->curTotal . " ---senddata: " . $this->curSendData . " ---result:" . print_r($result,true) . " ---runtime: " . $runTime, -__LINE__);
        }

        self::$connectionPool[$this->curAddress]['state'] = self::STATE_IDLE;

        $debugBody = [];
        $debugBody['type'] = 'RPC';
        $debugBody['link'] = $this->curAddress;
        $debugBody['command'] = $this->curSendData;
        $debugBody['state'] = true;
        $debugBody['runtime'] = $runTime;

        $step = Debug::getStep();
        Debug::data($step, $debugBody);
        if (isset($result['debug'])) {
            Debug::data($step . ':child', $result['debug']);
            unset($result['debug']);
        }

        return $result;
    }

    /**
     * 设置连接
     * @throws \Exception
     */
    protected function setConnection()
    {
        $this->curAddress = null;
        $this->connection = null;

        if (!$this->openConnection()) {
            throw new \Exception("can not connect to server", -__LINE__);
        }
    }

    protected function openConnection()
    {
        $addressArray = $this->parseAddressArray(self::$addressArray, []);
        foreach ($addressArray as $address) {

            $this->curAddress = $address;

            if ($this->getConnectionFormPool()) {
                //return true;
            }

            if ($this->createConnection()) {

                return true;
            }
        }
        return false;
    }

    /**
     * 首次创建连接
     * @param $address
     * @return bool
     */
    protected function createConnection()
    {
        //首次建立连接
        $this->connection = stream_socket_client($this->curAddress, $err_no, $err_msg);

        $this->curTotal = 1;

        if ($this->connection) {
            stream_set_blocking($this->connection, true);
            stream_set_timeout($this->connection, 5);

            self::$connectionPool[$this->curAddress]['handle'] = $this->connection;
            self::$connectionPool[$this->curAddress]['state'] = self::STATE_IDLE;
            self::$connectionPool[$this->curAddress]['total'] = $this->curTotal;
            return true;
        }
        $this->closeConnection();

        return false;
    }

    /**
     * 从连接池中获取已有连接
     * @param $address
     * @return bool
     */
    protected function getConnectionFormPool()
    {
        //使用连接池中连接
        if (self::$connectionPool[$this->curAddress]) {
            //连接对象存在
            if (isset(self::$connectionPool[$this->curAddress]['handle'])) {
                //并且连接状态为空闲
                if (self::$connectionPool[$this->curAddress]['state'] == self::STATE_IDLE) {

                    $this->connection = self::$connectionPool[$this->curAddress]['handle'];
                    //统计次数
                    self::$connectionPool[$this->curAddress]['total'] = intval(self::$connectionPool[$this->curAddress]['total']) + 1;
                    $this->curTotal = self::$connectionPool[$this->curAddress]['total'];
                    return true;
                } else {
                    //释放连接
                    fclose(self::$connectionPool[$this->curAddress]['handle']);
                    self::$connectionPool[$this->curAddress] = null;
                }
            }
        }

        return false;
    }

    /**
     * 按权重 获取地址随机序列
     * @param $tmpAddressArray
     * @param $newAddressArray
     *
     * @return array
     */
    protected function parseAddressArray($tmpAddressArray, $newAddressArray)
    {
        if (is_array($tmpAddressArray) && count($tmpAddressArray) > 0) {
            $rand = $this->randWeight($tmpAddressArray);
            $address = $tmpAddressArray[$rand];
            unset($tmpAddressArray[$rand]);
            $addressItem = $this->parseAddress($address);
            $newAddressArray[] = $addressItem[0];

            return $this->parseAddressArray($tmpAddressArray, $newAddressArray);
        } else {
            return $newAddressArray;
        }
    }

    /**
     * 解析带权重的配置项
     * @param $address
     *
     * @return array
     */
    protected function parseAddress($address)
    {
        $result = explode(',', $address);
        return $result;
    }

    /**
     * 按权重 取
     * @param $items
     *
     * @return int|string
     */
    protected function randWeight($items)
    {
        $weights = array();
        foreach ($items as $k => $v) {
            $addressItem = $this->parseAddress($v);
            if (count($addressItem) > 1) {
                $weights[$k] = $addressItem[1];
            } else {
                $weights[$k] = 1; // 如果没有配置权重 则权重为1
            }
        }

        $roll = rand(1, array_sum($weights));

        $_tmpW = 0;
        foreach ($weights as $k => $v) {
            $min = $_tmpW;
            $_tmpW += $v;
            $max = $_tmpW;

            if ($roll > $min && $roll <= $max) {
                return $k;
            }
        }
    }

    /**
     * 关闭到服务端的连接
     * @return void
     */
    protected function closeConnection()
    {
        fclose($this->connection);
        $this->connection = null;
        self::$connectionPool[$this->curAddress] = null;
    }
}

function create_JsonProtocol()
{
    /**
     * RPC 协议解析 相关
     * 协议格式为 [json字符串\n]
     * @author walkor <worker-man@qq.com>
     * */
    class JsonProtocol
    {
        /**
         * 从socket缓冲区中预读长度
         * @var integer
         */
        const PRREAD_LENGTH = 87380;

        /**
         * 判断数据包是否接收完整
         * @param string $bin_data
         * @param mixed $data
         * @return integer 0代表接收完毕，大于0代表还要接收数据
         */
        public static function dealInput($bin_data)
        {
            $bin_data_length = strlen($bin_data);
            // 判断最后一个字符是否为\n，\n代表一个数据包的结束
            if ($bin_data[$bin_data_length - 1] != "\n") {
                // 再读
                return self::PRREAD_LENGTH;
            }
            return 0;
        }

        /**
         * 将数据打包成Rpc协议数据
         * @param mixed $data
         * @return string
         */
        public static function encode($data)
        {
            return json_encode($data) . "\n";
        }

        /**
         * 解析Rpc协议数据
         * @param string $bin_data
         * @return mixed
         */
        public static function decode($bin_data)
        {
            return json_decode(trim($bin_data), true);
        }
    }
}

// ==以下调用示例==
if (PHP_SAPI == 'cli' && isset($argv[0]) && $argv[0] == basename(__FILE__)) {
    // 服务端列表
    $address_array = array('tcp://127.0.0.1:2015', 'tcp://127.0.0.1:2015');
    // 配置服务端列表
    RpcClient::config($address_array);

    $uid = 567;
    $user_client = RpcClient::instance('User');
    // ==同步调用==
    $ret_sync = $user_client->getInfoByUid($uid);

    // ==异步调用==
    // 异步发送数据
    $user_client->asend_getInfoByUid($uid);
    $user_client->asend_getEmail($uid);

    /**
     * 这里是其它的业务代码
     * ..............................................
     **/

    // 异步接收数据
    $ret_async1 = $user_client->arecv_getEmail($uid);
    $ret_async2 = $user_client->arecv_getInfoByUid($uid);

    // 打印结果
    var_dump($ret_sync, $ret_async1, $ret_async2);
}
