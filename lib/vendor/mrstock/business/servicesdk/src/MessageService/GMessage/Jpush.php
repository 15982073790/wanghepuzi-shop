<?php
namespace MrStock\Business\ServiceSdk\MessageService\GMessage;
use MrStock\Business\ServiceSdk\MessageService\GMessage;


/**
* 站内信服务-推送服务
*/
class Jpush extends GMessage
{
	CONST RESOURSE = 'jpush';
	
	public function __construct($client)
	{
		parent::__construct($client,self::RESOURSE);
	}

	/**
	* push推送
	* @param array $postdata push推送数组
	*
	* @desc 以下为新品首发 push 推送所需字段示例
	* @param string $postdata.type 推送类型 eg.newCourse(新品首发)	必填
	* @param string $postdata.ActId 推送跳转地址，各APP同客户端约定	必填
	* @param string/array $postdata.member_ids 推送用户ID(英文逗号分隔字符串或者数组)	必填
	* @param string $postdata.object_id 专题ID	根据需求选填
	* @param string $postdata.object_title 专题标题	根据需求选填
	* @param string $postdata.object_intro 专题简介	根据需求选填
	* @param string $postdata.seller_id 发布老师ID	根据需求选填
	* @param string $postdata.seller_name 发布老师名称	根据需求选填
	* @return array
	*/
	public function postData($postdata)
	{
		$response = $this->post($this->uri, $postdata);
		return $response;
	}

	/**
	* 股信推送
	* @param array $postdata push推送数组
	*
	* @desc 以下为新品首发 push 推送所需字段示例
	* @param string $postdata.type 推送类型 eg.new_report(新研报)	必填
	* @param string $postdata.ActId 推送跳转地址，各APP同客户端约定	必填
	* @param string/array $postdata.member_ids 推送用户ID(英文逗号分隔字符串或者数组)	必填
	* @param string $postdata.object_id 专题ID	根据需求选填
	* @param string $postdata.object_title 专题标题	根据需求选填
	* @return array
	*/
	public function GXPush($postdata)
	{
		$response = $this->post($this->uri.'/gx_push', $postdata);
		return $response;
	}

	/**
	* 第三方推送
	* @param array $postdata push推送数组
	* @desc 以下为 push 推送所需字段示例
	* @param string $postdata.type 推送类型 eg.new_report(新研报)	必填
	* @param string $postdata.ActId 推送跳转地址，各APP同客户端约定	必填
	* @param string/array $postdata.member_ids 推送用户ID(英文逗号分隔字符串或者数组)	必填
	* @param string $postdata.object_id 专题ID	根据需求选填
	* @param string $postdata.object_title 专题标题	根据需求选填
	* @return array
	*/
	public function ThirdPush($postdata)
	{
		$response = $this->post($this->uri.'/third_push', $postdata);
		return $response;
	}

	/**
	* 股信标的推送
	* @param array $postdata push推送数组
	*
	* @desc 以下为股票标的 push 推送所需字段示例
	* @param string $postdata.type 推送类型 eg.biaodi(股票标的)/dynamic(操盘动态)	必填
	* @param string $postdata.teacher_id 推送人 eg.20(老师id)必填
	* @param string $postdata.stock_id  消息id eg.20(标的id)必填
	* @param string/array $postdata.send_tag 推送商品与用户关系标识：eg.gx_10000001_106_100001(标的)/eg.gx_10000001_106_100001_stockId(操盘动态)(英文逗号分隔字符串或者数组) 必填
	* @return array
	*/
	public function GXstock_push($postdata)
	{
		$response = $this->post($this->uri.'/gx_stockpush', $postdata);
		return $response;
	}
}