<?php
namespace MrStock\Business\ServiceSdk\ImService\GIM;

use MrStock\Business\ServiceSdk\ImService\GIM;

/**
 * 群组与用户关系处理
 * Class GroupUser
 * @package GxsIM\GIM
 */
class GroupUser extends GIM
{
    CONST RESOURCE = 'groupuser';

    public function __construct($client)
    {
        parent::__construct($client, self::RESOURCE);
    }


    /**
     * 添加用户
     */
    public function addUser($body, $version='1.0')
    {
        $response = $this->post($this->uri.'/add', $body);
        return $response;
    }

    /**
     * 删除用户
     */
    public function delUser($body, $version='1.0')
    {
        $response = $this->put($this->uri.'/del',$body);
        return $response;
    }

    /**
     * 更新群成员
     * @param int $group_id 加入的群组id
     * @param int $type 类型
     * @param array $members 入群的成员id
     * @return object
     */
    public function updateGroupUsers($group_id,$type,array $members)
    {
        $this->_msgIdParamCheck($group_id);
        $group = [
            'update_type'  =>$type,
            'uid_list'  =>$members,
        ];
        $response = $this->put($this->uri.'/'.$group_id, $group);
        return $response;
    }

    /**
     * 获取群成员
     * @param int $group_id 群组id
     * @return object
     */
    public function getGroupUsers($group_id)
    {
        $this->_msgIdParamCheck($group_id);

        $response = $this->get($this->uri.'/'.$group_id);
        return $response;
    }

}