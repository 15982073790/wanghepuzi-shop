<?php

namespace Common\Model;

use MrStock\System\Orm\Model;

class User extends Model
{
    public function __construct()
    {
        parent::__construct('user');
    }
    public function selectByCurpage($request){
        $user_name_tel = $request['user_name_tel'];
        $curpage = $request['curpage'];
        $pagesize = $request['pagesize'];
        if(!empty($user_name_tel)){
             $where['user_name|tel'] = ['like',"{$user_name_tel}%"];
        }
        return $this->where($where)
            ->page($curpage,$pagesize)
            ->order('user_id desc')
            ->select();
    }
    public function countByCurpage($request){
        $user_name_tel = $request['user_name_tel'];
        if(!empty($user_name_tel)){
            $where['user_name|tel'] = ['like',"{$user_name_tel}%"];
        }
        return $this->where($where)->count();
    }
    public function insertData($data){
        return $this->insert($data);
    }
    public function findByCondition($where,$field='*'){
        return $this->field($field)
            ->where($where)
            ->find();
    }
    public function updateData($where,$data){
        return $this->where($where)
            ->update($data);
    }
    public function selectByCondition($where,$field='*'){
        return $this->field($field)
            ->where($where)
            ->limit(false)
            ->select();
    }
    public function setIncByCondition($where,$field,$num){
        return $this->where($where)->setInc($field,$num);
    }
}