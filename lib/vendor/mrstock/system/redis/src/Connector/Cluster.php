<?php
namespace MrStock\System\Redis\Connector;

use MrStock\System\Helper\Config;
use MrStock\System\Redis\Redis;
use MrStock\System\Redis\Rediscluster;

/**
 * Redishelper 操作类
 */
class Cluster extends Connector
{


    public function get()
    {
        $parameters = $this->config[$this->host]['seeds_nodes'];
        
        $this->handle= new Rediscluster($parameters);
        $this->setProperty();
        
        return $this->handle;
    }
}