<?php

namespace Inneruse\Control;


use MrStock\System\MJC\Control;
use MrStock\System\MJC\Http\Request;
use MrStock\System\Helper\Arr;
use Common\Model\Address;
use Common\Model\Area;
use MrStock\System\Helper\Config;
use Common\Libraries\AppTools;

/**
 * @@OpDescription(whatFor="地址",codeMonkey="")
 */
class AddressControl extends Control
{
    public $middleware = [
    ];

    /**
     * @@OpDescription(whatFor="根据条件查询地址信息",codeMonkey="")
     */
    public function findbyconditionOp(Request $request,Address $new_address,Area $new_area){
        $where = json_decode($request['where'],true);
        $other_where = json_decode($request['other_where'],true);
        $field = $request['field'] ?: '*';
        $address_find = $new_address->findByCondition($where,$field,$other_where);
        //获取省市区名
        $area_arr = [$address_find['province_id'],$address_find['city_id'],$address_find['county_id']];
        $area_arr = $new_area->selectByCondition(['area_id'=>['in',$area_arr]],'area_id,area_name');
        $area_arr = Arr::arrayToArrayKey($area_arr,'area_id');
        //结束
        //组合信息
        $address_find['province_name'] = $area_arr[$address_find['province_id']]['area_name'];
        $address_find['city_name'] = $area_arr[$address_find['city_id']]['area_name'];
        $address_find['county_name'] = $area_arr[$address_find['county_id']]['area_name'];

        $res = $address_find;
        return $this->json($res);
    }
}
