<?php

namespace MrStock\Business\ServiceSdk\ImService\GIM;

use MrStock\Business\ServiceSdk\ImService\GIM;

/**
* 用户动作控制器
*/
class User extends GIM
{
    CONST RESOURCE = 'user/';

    public function __construct($client)
    {
        parent::__construct($client, self::RESOURCE);
    }

    /**
     * @param array $body 用户最近讲话时间
     * @return object 返回资源
     * @throws \GxsIM\Exceptions\InvalidArgumentException
     */
    public function lastTalkTime($body)
    {
        $response = $this->post($this->uri.'last_talk_time', $body);
        return $response;
    }

    /**
     * @param array $body 用户在线状态
     * @return object 返回资源
     * @throws \GxsIM\Exceptions\InvalidArgumentException
     */
    public function onlineStatus($body)
    {
        $response = $this->get($this->uri.'online_status', $body);
        return $response;
    }
}