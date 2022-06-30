<?php

namespace Common\Model;


use MrStock\System\Helper\Arr;
use MrStock\System\Helper\Tool;
use MrStock\System\Orm\Model;

//
class AdminbussinessteamModel extends Model
{
    public function __construct()
    {
        parent::__construct('admin_bussinessteam');
    }

    /**
     * @param $condition
     * @param string $field
     * @return mixed
     * 根据条件获取账号集合的附加信息
     */
    public function getAdminBussinessteamInfo($condition, $field = '*')
    {
        $result = [];
        $adminlist = $this->field($field)->where($condition)->limit(false)->select();

        if (!empty($adminlist)) {
            $result = Arr::arrayToArrayKey($adminlist, "admin_id");
        }
        return $result;
    }

}