<?php
namespace MrStock\Business\ServiceSdk\MessageService;

/**
* 验证访问权限app_code与app_key
*/
class MessageClient
{
	private $authorization;

	/**
	* 初始化认证信息
	*/
	public function __construct($uid,$token)
	{
		$this->authorization = ['servicesuid' => $uid, 'servicestoken' => $token];
	}

	/**
	* 获取认证加密串
	*/
	public function getAuth(){
		return $this->authorization;
	}
}