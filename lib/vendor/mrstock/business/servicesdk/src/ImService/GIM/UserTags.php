<?php
namespace MrStock\Business\ServiceSdk\ImService\GIM;

use MrStock\Business\ServiceSdk\ImService\GIM;

/**
* 用户标签控制器
*/
class UserTags extends GIM
{
    CONST RESOURCE = 'user_tags';

    public function __construct($client)
    {
        parent::__construct($client, self::RESOURCE);
    }


    /**
     * 用户打标
     * @param string $tag_name 标签名称
     * @param array $user_ids 用户ID数组
     */
    public function tagAdd($tag_name, $user_ids, $version='1.0')
    {
        $body = [
            'tag_name' => $tag_name,
            'user_ids' => $user_ids
        ];
        $response = $this->post($this->uri.'/tag_add', $body);
        return $response;
    }

    /**
     * 用户去标
     * @param string $tag_name 标签名称
     * @param array $user_ids 用户ID数组
     */
    public function tagDel($tag_name, $user_ids, $version='1.0')
    {
        $body = [
            'tag_name' => $tag_name,
            'user_ids' => $user_ids
        ];
        $response = $this->put($this->uri.'/tag_del', $body);
        return $response;
    }

    /**
     * 用户标签获取
     * @param string $user_id 用户ID
     * @param array $tag_names 查询标签数组
     */
    public function getUserTags($user_id, $tag_names, $version='1.0')
    {
        $body = [
            'user_id' => $user_id,
            'tag_names' => $tag_names
        ];
        $response = $this->get($this->uri.'/get_user_tags', $body);
        return $response;
    }

    /**
     * 获取标签用户
     * @param string $tag_name 标签名称
     */
    public function getTagUsers($tag_name, $version='1.0')
    {
        $body = ['tag_name' => $tag_name];
        $response = $this->get($this->uri.'/get_tag_users', $body);
        return $response;
    }

    /**
     * 拷贝标签
     * @param string $tag_name 标签名称
     */
    public function copyTagName($src_tag_name, $des_tag_name, $version='1.0')
    {
        $body = ['src_tag_name' => $src_tag_name, 'dest_tag_name'=>$des_tag_name];
        $response = $this->get($this->uri.'/copy_tag_name', $body);
        return $response;
    }

}