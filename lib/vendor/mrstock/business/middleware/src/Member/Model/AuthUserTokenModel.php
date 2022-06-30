<?php
namespace MrStock\Business\Middleware\Member\Model;

use MrStock\System\Orm\Model;
use MrStock\Business\Middleware\Member\Facade\RedisFacade;
/**
 * 手机端令牌验证模型
 *
 */
class AuthUserTokenModel extends Model
{

    public function __construct()
    {
        parent::__construct('user_token','framework');
    }

    /**
     * 查询
     *
     * @param array $condition
     *            查询条件
     * @return array
     */
    public function getUserTokenInfo($condition, $fields = '*')
    {
        $member_id = intval($condition['member_id']);
        if ($condition['route_mark'] != '' && $condition['route_mark'] != 'app') {
            $user_token_info = RedisFacade::get($condition['route_mark'] . '_user_tokens:' . $member_id);
        }
        if (empty($user_token_info)) {
            $condition['route_mark'] = 'app';
            $user_token_info = RedisFacade::get('user_tokens:' . $member_id);
        }
        
        $user_token = unserialize($user_token_info);

        if (empty($user_token)) {
            $where = $condition;
            unset($where['route_mark']);
            $user_token = $this->where($where)->find();
            
            if (! empty($user_token)) {
                if ($condition['route_mark'] != 'app' && $condition['route_mark'] != '') {
                    RedisFacade::set($condition['route_mark'] . '_user_tokens:' . $member_id, serialize($user_token));
                } else {
                    RedisFacade::set('user_tokens:' . $member_id, serialize($user_token));
                }
            }
        }
        return $user_token;
    }

    public function getUserTokenInfoByID($member_id, $route_mark = '', $key = '')
    {
        if (empty($member_id)) {
            return null;
        }
        
        switch ($route_mark) {
            default:
                return $this->getUserTokenInfo(array(
                    'member_id' => $member_id,
                    'token' => $key,
                    'route_mark' => $route_mark
                ));
        }
    }
}