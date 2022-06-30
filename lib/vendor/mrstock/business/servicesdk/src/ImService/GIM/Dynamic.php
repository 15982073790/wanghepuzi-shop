<?php

namespace MrStock\Business\ServiceSdk\ImService\GIM;

use MrStock\Business\ServiceSdk\ImService\GIM;

/**
* 私股圈动态相关
*/
class Dynamic extends GIM
{
    CONST RESOURCE = 'dynamic';

    public function __construct($client)
    {
        parent::__construct($client, self::RESOURCE);
    }

    /**
     * 添加动态
     */
    public function create($body, $version='1.0')
    {
        $response = $this->post($this->uri.'/create', $body);
        return $response;
    }

    /**
     * 点赞
     */
    public function thumbsUp($body, $version='1.0')
    {
        $response = $this->put($this->uri.'/thumbs_up', $body);
        return $response;
    }

    /**
     * 评论
     */
    public function comment($body, $version='1.0')
    {
        $response = $this->post($this->uri.'/comment', $body);
        return $response;
    }

    /**
     * 评论回复
     */
    public function commentReply($body, $version='1.0')
    {
        $response = $this->post($this->uri.'/commentReply', $body);
        return $response;
    }

    /**
     * 股圈动态详情
     */
    public function detail($body, $version='1.0')
    {
        $response = $this->get($this->uri.'/detail', $body);
        return $response;
    }

    /**
     * 股圈动态评论列表
     */
    public function commentList($body, $version='1.0')
    {
        $response = $this->get($this->uri.'/commentList', $body);
        return $response;
    }

    /**
     * 股圈动态老师回复撤回
     */
    public function replyRetract($body, $version='1.0')
    {
        $response = $this->post($this->uri.'/replyRetract', $body);
        return $response;
    }

    /**
     * 评论审核（更改公开状态）
     */
    public function changeOpenStatus($body, $version='1.0')
    {
        $response = $this->post($this->uri.'/changeOpenStatus', $body);
        return $response;
    }

    /**
     * 股圈列表(用户端)
     */
    public function userList($body, $version='1.0')
    {
        $response = $this->get($this->uri.'/user_list', $body);
        return $response;
    }

    /**
     * 股圈单条动态详情(用户端)
     */
    public function userMsgDetail($body, $version='1.0')
    {
        $response = $this->get($this->uri.'/user_msg_detail', $body);
        return $response;
    }

    /**
     * 股圈列表(老师端)
     */
    public function adminList($body, $version='1.0')
    {
        $response = $this->get($this->uri.'/admin_list', $body);
        return $response;
    }

    /**
     * 新消息列表
     */
    public function newNoticeList($body, $version='1.0')
    {
        $response = $this->get($this->uri.'/new_notice_list', $body);
        return $response;
    }

    /**
     * 新消息提醒
     */
    public function newNoticeInfo($body, $version='1.0')
    {
        $response = $this->get($this->uri.'/new_notice_info', $body);
        return $response;
    }
    /**
     * 私股圈删除（撤回）
     */
    public function retract($body, $version='1.0')
    {
        $response = $this->post($this->uri.'/retract', $body);
        return $response;
    }
    /**
     * 老师私股圈最新消息
     */
    public function recentMsg($body, $version='1.0')
    {
        $response = $this->get($this->uri.'/recent_msg', $body);
        return $response;
    }
}