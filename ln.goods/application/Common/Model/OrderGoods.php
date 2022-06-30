<?php

namespace Common\Model;

use MrStock\System\Orm\Model;

class OrderGoods extends Model
{
    public function __construct()
    {
        parent::__construct('order_goods');
    }

    public function insertAllData($data){
        return $this->insertAll($data);
    }
    public function updateData($where,$data){
        return $this->where($where)->update($data);
    }
    public function findByCondition($where,$field='*'){
        return $this->field($field)
            ->where($where)
            ->find();
    }
    public function selectByCondition($where,$field='*'){
        return $this->field($field)
            ->where($where)
            ->limit(false)
            ->select();
    }
    public function selectByOrdergoodsGoods($where,$other_where,$field='*'){
        $build = $this->table('order_goods,goods')
            ->join('left')
            ->on('order_goods.goods_id=goods.goods_id')
            ->field($field)
            ->where($where);
        if(!empty($other_where['order'])){
            $build->order($other_where['order']);
        }
        return $build->limit(false)->select();
    }

}