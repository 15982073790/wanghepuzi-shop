<?php
namespace MrStock\Business\ServiceSdk\MessageService\GMessage;
use MrStock\Business\ServiceSdk\MessageService\GMessage;

/**
* 
*/
class ListInfo extends GMessage
{
	CONST RESOURCE = 'listUnread';

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
	public function Unread($member_id,$tag='')
	{
		$data = [
			'member_id'=>$member_id,
			'tag'=>$tag,
		];

		$response = $this->get($this->uri,$data);
		return $response;
	}

}