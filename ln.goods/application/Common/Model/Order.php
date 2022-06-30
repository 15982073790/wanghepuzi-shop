<?php

namespace Common\Model;

use MrStock\System\Orm\Model;

class Order extends Model
{
    public function __construct()
    {
        parent::__construct('order');
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
        return $this->where($where)->update($data);
    }
    public function selectByCondition($where,$order,$field='*'){
        $build = $this->field($field)
            ->where($where);
        if(!empty($order)){
            $build->order($order);
        }
        $build->limit(false);
        return $build->select();
    }
    public function selectByCurpage($curpage,$pagesize,$where,$field='*'){
        return $this->field($field)
            ->where($where)
            ->order('order_id desc')
            ->page($curpage,$pagesize)
            ->select();
    }
    public function countByCurpage($where,$field='*'){
        return $this->field($field)
            ->where($where)
            ->count();
    }






}