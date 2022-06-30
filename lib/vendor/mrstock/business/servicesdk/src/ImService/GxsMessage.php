<?php

namespace MrStock\Business\ServiceSdk\ImService;

/**
* 
*/
class GxsMessage
{
    private $authorization;

    /**
    * 初始化认证信息
    */
    public function __construct($token)
    {
        $this->authorization = ['servicestoken' => $token];
    }

    /**
    * 获取认证加密串
    */
    public function getAuth(){
        return $this->authorization;
    }
}