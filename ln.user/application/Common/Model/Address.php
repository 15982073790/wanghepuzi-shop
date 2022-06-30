<?php

namespace Common\Model;

use MrStock\System\Orm\Model;

class Address extends Model
{
    public function __construct()
    {
        parent::__construct('address');
    }
    public function findByCondition($where=[],$field='*',$other_where){
        $this->field($field)
            ->where($where);
        if (!empty($other_where['order'])) {
            $this->order($other_where['order']);
        }
        return $this->find();
    }
    public function selectByCondition($where=[],$field='*',$other_where){
        $this->field($field)
            ->where($where);
        if (!empty($other_where['order'])) {
            $this->order($other_where['order']);
        }
        return $this->limit(false)
            ->select();
    }
    public function countByCondition($where=[],$field='*'){
        return $this->field($field)
            ->where($where)
            ->count();
    }
    public function updateData($where,$data){
        return $this->where($where)->update($data);
    }

}