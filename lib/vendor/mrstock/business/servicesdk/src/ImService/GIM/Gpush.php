<?php

namespace MrStock\Business\ServiceSdk\ImService\GIM;

use MrStock\Business\ServiceSdk\ImService\GIM;

/**
* 群组统一管理控制器
*/
class Gpush extends GIM
{
    CONST RESOURCE = 'gpush';

    public function __construct($client)
    {
        parent::__construct($client,self::RESOURCE);
    }

    /**
     * 推送
     * @param array $body 推送数据
     * @param array $body.title 推送标题
     * @param array $body.jump 推送跳转
     * @param array $body.union_uids 接收用户
     */
    public function send($body)
    {
        $response = $this->post($this->uri.'/send', $body);
        return $response;
    }
}
