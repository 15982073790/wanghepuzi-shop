<?php
namespace App\Model;

use MrStock\System\Orm\Model;
use MrStock\System\Helper\Log;
use MrStock\Business\Base\Logic\ReLogic;
/**
 * 登录令牌操作类
 *
 */
class UserTokenModel extends Model{
    public function __construct(){
        parent::__construct('user_token');
    }

    /**
     * 新增
     *
     * @param array $param 参数内容
     * @return bool 布尔类型的返回结果
     */
    public function addUserToken($param){
        $this->beginTransaction();
        $member_id  = intval($param['member_id']);
        if($this->insert($param)){

            $user_token = serialize ( $param );

            if($param['route_mark']!= 'app' && $param['route_mark']!=''){
                ReLogic::wkcache ( $param['route_mark'].'_user_tokens:'.$member_id, $user_token);
            }else{
                ReLogic::wkcache ( 'user_tokens:'.$member_id, $user_token);
            }

            /*if(empty($rs))//写入redis失败 回滚 数据库
            {
                $this->rollback();
                return false;
            }*/
            Log::record('写入redis结果：'.$param['member_id'].'==='.var_export($rs, true));
            $this->commit();
            return 1;
        }
        $this->rollback();
        return false;
    }

    /**
     * 删除
     *
     * @param int $condition 条件
     * @return bool 布尔类型的返回结果
     */
    public function delUserToken($condition){
        $member_id  = intval($condition['member_id']);
        $list[$member_id] = 0;
        if($condition['route_mark'] != 'app' && $condition['route_mark']!=''){
            
            ReLogic::dkcache ( $condition['route_mark'].'_user_tokens:'.$member_id);
        }else{
            
            ReLogic::dkcache ( 'user_tokens:'.$member_id);
        }

        ReLogic::dkcache ( "members:".$member_i);

        $condition['route_mark'] = empty($condition['route_mark'])?'app':$condition['route_mark'];
        return $this->where($condition)->delete();
    }
    /**
     * 读取一条数据
     * @param array $condition
     *
     */
    public function getOne($condition,$order='',$field='*'){
        $result = $this->table('user_token')->field($field)->where($condition)->order($order)->find();
        return $result;
    }
}