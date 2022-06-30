<?php

namespace MrStock\Business\ServiceSdk\ImService\GIM;

use MrStock\Business\ServiceSdk\ImService\GIM;

/**
* 消息统一管理控制器
*/
class Message extends GIM
{
    CONST RESOURCE = 'message';

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
     * 消息回执
     * @param array $body
     * @return object
     * @throws \GxsIM\Exceptions\InvalidArgumentException
     */
    public function receipt($body)
    {
        $response = $this->put($this->uri.'/receipt', $body);
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
     * search
     * @param array $body 参数
     * @return object
     * @throws \GxsIM\Exceptions\InvalidArgumentException
     */
    public function search($body)
    {
        $response = $this->get($this->uri.'/search', $body);
        return $response;
    }

    /**
     * search
     * @param array $body 参数
     * @return object
     * @throws \GxsIM\Exceptions\InvalidArgumentException
     */
    public function comment($body)
    {
        $response = $this->post($this->uri.'/comment', $body);
        return $response;
    }

    /**
     * search
     * @param array $body 参数
     * @return object
     * @throws \GxsIM\Exceptions\InvalidArgumentException
     */
    public function commentList($body)
    {
        $response = $this->get($this->uri.'/comment_list', $body);
        return $response;
    }

    /**
     * search
     * @param array $body 参数
     * @return object
     * @throws \GxsIM\Exceptions\InvalidArgumentException
     */
    public function retractList($body)
    {
        $response = $this->get($this->uri.'/retract_list', $body);
        return $response;
    }

    /**
     * verify list
     * @param array $body 参数
     * @return object
     * @throws \GxsIM\Exceptions\InvalidArgumentException
     */
    public function verifyList($body)
    {
        $response = $this->get($this->uri.'/verify_list', $body);
        return $response;
    }

    /**
     * verify list
     * @param array $body 参数
     * @return object
     * @throws \GxsIM\Exceptions\InvalidArgumentException
     */
    public function verifyStatus($body)
    {
        $response = $this->post($this->uri.'/verify_status', $body);
        return $response;
    }

    /**
     * comment list
     * @param array $body 参数
     * @return object
     * @throws \GxsIM\Exceptions\InvalidArgumentException
     */
    public function adminCommentList($body)
    {
        $response = $this->get($this->uri.'/admin_comment_list', $body);
        return $response;
    }

    /**
     * comment list
     * @param array $body 参数
     * @return object
     * @throws \GxsIM\Exceptions\InvalidArgumentException
     */
    public function msgList($body)
    {
        $response = $this->get($this->uri.'/list', $body);
        return $response;
    }

    /**
     * 群股票列表
     * @param array $body 参数
     * @return object
     * @throws \GxsIM\Exceptions\InvalidArgumentException
     */
    public function stockList($body)
    {
        $response = $this->get($this->uri.'/stock_list', $body);
        return $response;
    }
}























