<?php

namespace MrStock\Business\ServiceSdk\ImService\GIM;

use MrStock\Business\ServiceSdk\ImService\GIM;

/**
* 消息统一管理控制器
*/
class DialogBox extends GIM
{
    CONST RESOURCE = 'dialogbox/';

    public function __construct($client)
    {
        parent::__construct($client, self::RESOURCE);
    }

    /**
     * 对话框列表
     * @return object 返回资源
     * @throws \GxsIM\Exceptions\InvalidArgumentException
     */
    public function boxList($uid, $page = 1, $pagesize = 20)
    {
        $body = [
            'uid' => $uid, 
            'page' => $page,
            'pagesize' => $pagesize
        ];
        $response = $this->get($this->uri.'list', $body);
        return $response;
    }

    /**
     * 对话框消息列表
     * @return object 返回资源
     * @throws \GxsIM\Exceptions\InvalidArgumentException
     */
    public function message($body, $page = 1, $pagesize = 20)
    {
        $body['page'] = $page;
        $body['pagesize'] = $pagesize;
        $response = $this->get($this->uri.'message', $body);
        return $response;
    }

    /**
     * 对话框置顶
     * @return object 返回资源
     * @throws \GxsIM\Exceptions\InvalidArgumentException
     */
    public function stick($body)
    {
        $response = $this->put($this->uri.'stick', $body);
        return $response;
    }

    /**
     * 对话框置顶取消
     * @return object 返回资源
     * @throws \GxsIM\Exceptions\InvalidArgumentException
     */
    public function stickCancel($body)
    {
        $response = $this->put($this->uri.'stickCancel', $body);
        return $response;
    }

    /**
     * 对话框创建
     * @return object 返回资源
     * @throws \GxsIM\Exceptions\InvalidArgumentException
     */
    public function create($body)
    {
        $response = $this->post($this->uri.'add', $body);
        return $response;
    }

    /**
     * 对话框删除
     * @return object 返回资源
     * @throws \GxsIM\Exceptions\InvalidArgumentException
     */
    public function del($body)
    {
        $response = $this->put($this->uri.'del', $body);
        return $response;
    }

    /**
     * 对话框免打扰设置
     * @return object 返回资源
     * @throws \GxsIM\Exceptions\InvalidArgumentException
     */
    public function disturb($body)
    {
        $response = $this->put($this->uri.'disturb', $body);
        return $response;
    }

    /**
     * 对话框免打扰，置顶状态
     * @return object 返回资源
     * @throws \GxsIM\Exceptions\InvalidArgumentException
     */
    public function boxInfo($body)
    {
        $response = $this->get($this->uri.'box_info', $body);
        return $response;
    }

    /**
     * 清空对话框消息
     * @return object 返回资源
     * @throws \GxsIM\Exceptions\InvalidArgumentException
     */
    public function emptyMsg($body)
    {
        $response = $this->post($this->uri.'empty_message', $body);
        return $response;
    }

    /**
     * 对话框已读
     * @return object 返回资源
     * @throws \GxsIM\Exceptions\InvalidArgumentException
     */
    public function boxRead($body)
    {
        $response = $this->put($this->uri.'box_read', $body);
        return $response;
    }    
}























