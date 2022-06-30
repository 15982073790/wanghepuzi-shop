<?php
namespace MrStock\Business\ServiceSdk\MessageService\GMessage;
use MrStock\Business\ServiceSdk\MessageService\GMessage;

/**
* 
*/
class Message extends GMessage
{
	CONST RESOURCE = 'messages';

	public function __construct($client)
	{
		parent::__construct($client,self::RESOURCE);
	}

	/**
	* 获取某个分组下消息列表[get]
	* @param string $member_id 用户id
	* @param string $tag 标签
	* @param int $curpage 当前页码
	* @param int $pagesize 每页条数
	* @return object 返回资源
	*/
	public function getList($member_id,$tag,$curpage = 1,$pagesize = 20)
	{
		$data = [
			'member_id' =>$member_id,
			'tag' =>$tag,
			'curpage' =>$curpage,
			'pagesize' =>$pagesize,
		];

		$response = $this->get($this->uri,$data);
		return $response;
	}

	/**
	* 标记已读[put]
	* @param string $member_id 用户id
	* @param string $message_id 消息id
	* @param string $tag 标签
	* @return object 返回资源
	*/
	public function read($member_id,$message_id,$tag)
	{
		$data = [
			'member_id' => $member_id,
			'tag' => $tag,
			'message_id' => $message_id
		];
		$this->uri = self::BASE_URL.'messageRead';
		$response = $this->put($this->uri,$data);
		return $response;
	}

	/**
	* 一键全看[put]
	* @param string $member_id
	* @return object 返回资源
	*/
	public function allRead($member_id)
	{	
		$data = [
			'member_id'=>$member_id,
		];
		$response = $this->put($this->uri,$data);
		return $response;
	}

}