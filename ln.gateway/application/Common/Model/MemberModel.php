<?php

namespace Common\Model;


use Common\Data\RoleData;
use MrStock\System\Helper\Tool;
use MrStock\System\Orm\Model;

class MemberModel extends Model
{
    public function __construct()
    {
        parent::__construct('member','useractivity');
    }


    public function findIdByJobNumber($job_number){
        return $this->field('id as member_id,tel')->where(['job_number'=>$job_number])->find();
    }


}