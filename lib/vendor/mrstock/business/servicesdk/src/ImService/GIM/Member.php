<?php

namespace MrStock\Business\ServiceSdk\ImService\GIM;

use MrStock\Business\ServiceSdk\ImService\GIM;
/**
* 用户动作控制器
*/
class Member extends GIM
{
    CONST RESOURCE = 'user';

    public function __construct($client)
    {
        parent::__construct($client, self::RESOURCE);
    }

    /**
     * 用户登入
     */
    public function online($client_id, $uid, $version='1.0')
    {
        $body = [
            'client_id' => $client_id,
            'uid' => $uid
        ];

        $response = $this->post($this->uri, $body);
        return $response;
    }

    /**
     * 用户登出
     */
    public function offline($uid, $version='1.0')
    {
         $body = [
            'uid' => $uid,
            'action_type' => 'offline'
        ];

        $response = $this->post($this->uri, $body);
        return $response;
    }
}