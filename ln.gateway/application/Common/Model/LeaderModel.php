<?php

namespace Common\Model;


use Common\Data\RoleData;
use MrStock\System\Helper\Tool;
use MrStock\System\Orm\Model;

class LeaderModel extends Model
{
    public function __construct()
    {
        parent::__construct('leader','useractivity');
    }

    public function findIdByTel($tel){
        return $this->field('id as leader_id,tel')->where(['tel'=>$tel])->find();
    }


}