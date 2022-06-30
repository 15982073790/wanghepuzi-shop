<?php

namespace Common\Model;

use MrStock\System\Orm\Model;

class Area extends Model
{
    public function __construct()
    {
        parent::__construct('area');
    }
    public function selectByCondition($where=[],$field='*'){
        return $this->field($field)
            ->where($where)
            ->limit(false)
            ->select();
    }

}