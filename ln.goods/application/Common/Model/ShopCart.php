<?php

namespace Common\Model;

use MrStock\System\Orm\Model;

class ShopCart extends Model
{
    public function __construct()
    {
        parent::__construct('shop_cart');
    }
    public function insertData($data){
        return $this->insert($data);
    }
    public function findByCondition($where,$field='*'){
        return $this->field($field)
            ->where($where)
            ->find();
    }
    public function setByConditionInc($where,$field='goods_count',$step = 1)
    {
        return $this->where($where)->setInc($field,$step);
    }
    public function setByConditionDec($where,$field='goods_count',$step = 1)
    {
        return $this->where($where)->setDec($field,$step);
    }
    public function updateData($where,$data){
        return $this->where($where)->update($data);
    }
    public function selectByShopcartGoods($where,$field='*'){
        return $this->table('shop_cart,goods')
            ->field($field)
            ->join('left')
            ->on('shop_cart.goods_id=goods.goods_id')
            ->where($where)
            ->limit(false)
            ->select();
    }
}