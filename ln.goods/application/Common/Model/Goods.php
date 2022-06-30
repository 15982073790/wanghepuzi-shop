<?php

namespace Common\Model;

use MrStock\System\Orm\Model;

class Goods extends Model
{
    public function __construct()
    {
        parent::__construct('goods');
    }
    public function selectByCurpage($request){
        $goods_name_sn = $request['goods_name_sn'];
        $curpage = $request['curpage'];
        $pagesize = $request['pagesize'];
        if(!empty($goods_name_sn)){
             $where['goods_name|goods_sn'] = ['like',"{$goods_name_sn}%"];
        }
        return $this->where($where)
            ->page($curpage,$pagesize)
            ->order('publish_status desc,banner_status desc,quality_goods_status desc,sort asc,goods_id desc')
            ->select();
    }
    public function countByCurpage($request){
        $goods_name_sn = $request['goods_name_sn'];
        if(!empty($goods_name_sn)){
            $where['goods_name|goods_sn'] = ['like',"{$goods_name_sn}%"];
        }
        return $this->where($where)->count();
    }
    public function findByCondition($where,$field="*"){
        return $this->field($field)
            ->where($where)
            ->find();
    }
    public function updateData($where,$data){
        return $this->where($where)->update($data);
    }
    public function insertData($data){
        return $this->insert($data);
    }
    public function insertAllData($data){
        return $this->insertAll($data);
    }
    public function countByCondition($where){
        return $this->where($where)->count();
    }
    public function selectByCondition($where,$field='*'){
        return $this->field($field)
            ->where($where)
            ->order('sort asc,goods_id desc')
            ->limit(false)
            ->select();
    }
    public function setIncByCondition($where,$field,$num){
        return $this->where($where)->setInc($field,$num);
    }
    public function setDecByCondition($where,$field,$num){
        return $this->where($where)->setDec($field,$num);
    }

}