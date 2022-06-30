<?php
namespace MrStock\Business\ServiceSdk\MessageService\GMessage;
use MrStock\Business\ServiceSdk\MessageService\GMessage;
/**
* 
*/
class MessageInfo extends GMessage
{
	CONST RESOURCE = 'messageUnread';
	
	public function __construct($client)
	{
		parent::__construct($client,self::RESOURCE);
	}

	/**
	* 获取总消息/分组消息未读条数[get]
	* @param string $member_id 用户id
	* @param string $tags 标签
	* @return object 返回资源
	*/
	public function unreadNum($member_id,$tags)
	{
		$data = [
			'member_id'=>$member_id,
			'tags'=>$tags,
		];

		$response = $this->get($this->uri,$data);
		return $response;
	}
}