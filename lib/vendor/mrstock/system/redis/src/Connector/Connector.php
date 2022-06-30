<?php
namespace MrStock\System\Redis\Connector;

use MrStock\System\Helper\Config;
use MrStock\System\Redis\Redis;

/**
 * Redishelper 操作类
 */
class Connector
{

    protected $config;

    protected $host;

    protected $db;

    protected $masterOrSlave;

    protected $readonly;

    protected $prefix;

    protected $handle;

    public function __construct($config, $host, $db, $prefix, $masterOrSlave, $readOnly)
    {
        $this->config = $config;
        $this->host = $host;
        $this->db = $db;
        $this->prefix = $prefix;
        $this->masterOrSlave = $masterOrSlave;
        $this->readonly = $readOnly;
    }

    protected function setProperty()
    {
        $this->handle->hashSharding = Config::get('redis_hashsharding');
        $this->handle->needReadonly = $this->readonly;
        $this->handle->prefix = $this->prefix;
    }
}