<?php
namespace MrStock\Business\ServiceSdk\MessageService\GMessage;
use MrStock\Business\ServiceSdk\MessageService\GMessage;

/**
* 
*/
class Lists extends GMessage
{
	CONST RESOURCE = 'list';

	public function __construct($client)
	{
		parent::__construct($client,self::RESOURCE);
	}

	/**
	* 获取某个分组下消息列表[get]
	* @param string $member_id 用户id
	* @param string $tag 标签
	* @param string $last_key 最后拼接的键名（如：操盘动态stock_id）
	* @param int $curpage 当前页码
	* @param int $pagesize 每页条数
	* @return object 返回资源
	*/
	public function getDataList($member_id,$tag='',$last_key='',$curpage = 1,$pagesize = 20)
	{
		$data = [
			'member_id' =>$member_id,
			'tag' =>$tag,
			'last_key' =>$last_key,
			'curpage' =>$curpage,
			'pagesize' =>$pagesize,
		];
		$response = $this->get($this->uri,$data);
		return $response;
	}

	/**
	* 一键全看[put]
	* @param string $member_id
	* @param string $tag
	* @param string $last_key
	* @return object 返回资源
	*/
	public function allRead($member_id,$tag='',$last_key='')
	{	
		$data = [
			'member_id'=>$member_id,
			'tag'=>$tag,
			'last_key'=>$last_key,
		];
		$response = $this->put($this->uri,$data);
		return $response;
	}

	/**
	* 用户所有的消息ids[get]
	* @param string $member_id
	* @param string $tag
	* @return object 返回资源
	*/
	public function getAllIds($member_id,$tag='')
	{	
		$data = [
			'member_id'=>$member_id,
			'tag'=>$tag,
		];
		$response = $this->get($this->uri.'/ids',$data);
		return $response;
	}

	/**
	* 检验用户产品id是否存在[get]
	* @param string $member_id
	* @param string $tag
	* @param string $id
	* @return bool布尔
	*/
	public function isExistId($member_id,$tag='',$id)
	{	
		$data = [
			'member_id'=>$member_id,
			'tag'=>$tag,
			'id'=>$id,
		];
		$response = $this->get($this->uri.'/id',$data);
		return $response;
	}

}