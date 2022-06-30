<?php

namespace MrStock\Business\ServiceSdk\ImService\GIM;

use MrStock\Business\ServiceSdk\ImService\GIM;

/**
* 消息群发
*/
class MessageMult extends GIM
{
    CONST RESOURCE = 'messagemult';

    public function __construct($client)
    {
        parent::__construct($client, self::RESOURCE);
    }

    /**
     * @param array $body 消息内容
     * @return object 返回资源
     * @throws \GxsIM\Exceptions\InvalidArgumentException
     */
    public function send($body)
    {
        $response = $this->post($this->uri.'/send', $body);
        return $response;
    }

    /**
     * 群发消息用户
     * @param array $body
     * @return object
     * @throws \GxsIM\Exceptions\InvalidArgumentException
     */
    public function msgUsers($body)
    {
        $response = $this->get($this->uri.'/msg_users', $body);
        return $response;
    }

    /**
     * 群发历史列表
     * @param array $body
     * @return object
     * @throws \GxsIM\Exceptions\InvalidArgumentException
     */
    public function sendList($body)
    {
        $response = $this->get($this->uri.'/send_list', $body);
        return $response;
    }

    /**
     * 撤回消息
     * @param array $body 参数
     * @return object
     * @throws \GxsIM\Exceptions\InvalidArgumentException
     */
    public function retract($body)
    {
        $response = $this->put($this->uri.'/retract', $body);
        return $response;
    }

    /**
     * 群发消息详情
     * @param array $body 参数
     * @return object
     * @throws \GxsIM\Exceptions\InvalidArgumentException
     */
    public function msgInfo($body)
    {
        $response = $this->get($this->uri.'/msg_info', $body);
        return $response;
    }
}























