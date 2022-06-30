<?php

namespace App\Control;

use MrStock\System\MJC\Control;
use MrStock\System\MJC\Http\Request;
use Common\Model\Address;
use Common\Model\Area;
use MrStock\System\Helper\Arr;
use MrStock\System\Helper\Config;
use Common\Libraries\AppTools;

/**
 * @OpDescription(whatFor="地址",codeMonkey="")
 */
class AddressControl extends Control
{
    public $middleware = [
        'control'         => [],
        'addaddressOp'    => [
            'Common\Middleware\Addressaddaddress'
        ],
        'updateaddressOp' => [
            'Common\Middleware\Addressupdateaddress'
        ]
    ];

    /**
     * @OpDescription(whatFor="收货地址列表",codeMonkey="")
     */
    public function addresslistOp(Request $request, Address $new_address, Area $new_area)
    {
        $wx_openid       = $request['wx_openid'];
        $address_arr     = $new_address->selectByCondition(['wx_openid' => $wx_openid],'*',['order'=>'address_default desc,address_id desc']);
        $province_id_arr = array_column($address_arr, 'province_id');
        $city_id_arr     = array_column($address_arr, 'city_id');
        $county_id_arr   = array_column($address_arr, 'county_id');
        $area_id_arr     = array_unique(array_merge($province_id_arr, $city_id_arr, $county_id_arr));
        $area_arr        = $new_area->selectByCondition(['area_id' => ['in', $area_id_arr]], 'area_id,area_name');
        $area_arr        = Arr::arrayToArrayKey($area_arr, 'area_id');
        foreach ($address_arr as $key => &$value) {
            $value['province_name'] = $area_arr[$value['province_id']]['area_name'];
            $value['city_name']     = $area_arr[$value['city_id']]['area_name'];
            $value['county_name']   = $area_arr[$value['county_id']]['area_name'];
            unset(
                $value['itime'],
                $value['utime'],
                $value['dtime'],
                $value['datastatus']
            );
        }
        $res = $address_arr;
        return $this->json($res);
    }

    /**
     * @OpDescription(whatFor="新增",codeMonkey="")
     */
    public function addaddressOp(Request $request, Address $new_address)
    {
        $wx_openid               = $request['wx_openid'];
        $true_name               = $request['true_name'];
        $address_tel             = $request['address_tel'];
        $province_id             = $request['province_id'];
        $city_id                 = $request['city_id'];
        $county_id               = $request['county_id'];
        $detail_address          = $request['detail_address'];
        $address_default         = $request['address_default'];
        $data['wx_openid']       = $wx_openid;
        $data['province_id']     = $province_id;
        $data['city_id']         = $city_id;
        $data['county_id']       = $county_id;
        $data['detail_address']  = $detail_address;
        $data['true_name']       = $true_name;
        $data['address_tel']     = $address_tel;
        $data['address_default'] = $address_default;
        if($address_default==2){
            $new_address->updateData(['wx_openid'=>$wx_openid,'address_default'=>2],['address_default'=>1]);
        }
        $new_address->insert($data);
        return $this->json('新增成功');
    }

    /**
     * @OpDescription(whatFor="编辑",codeMonkey="")
     */
    public function editaddressOp(Request $request, Address $new_address)
    {
        $wx_openid    = $request['wx_openid'];
        $address_id   = $request['address_id'];
        $address_find = $new_address->findByCondition(['wx_openid' => $wx_openid, 'address_id' => $address_id]);
        unset(
            $address_find['itime'],
            $address_find['utime'],
            $address_find['dtime'],
            $address_find['datastatus']
        );
        $res = $address_find;
        return $this->json($res);
    }

    /**
     * @OpDescription(whatFor="更新",codeMonkey="")
     */
    public function updateaddressOp(Request $request, Address $new_address)
    {
        $wx_openid  = $request['wx_openid'];
        $address_id = $request['address_id'];

        $true_name               = $request['true_name'];
        $address_tel             = $request['address_tel'];
        $province_id             = $request['province_id'];
        $city_id                 = $request['city_id'];
        $county_id               = $request['county_id'];
        $detail_address          = $request['detail_address'];
        $address_default         = $request['address_default'];
        $data['wx_openid']       = $wx_openid;
        $data['province_id']     = $province_id;
        $data['city_id']         = $city_id;
        $data['county_id']       = $county_id;
        $data['detail_address']  = $detail_address;
        $data['true_name']       = $true_name;
        $data['address_tel']     = $address_tel;
        $data['address_default'] = $address_default;
        if($address_default==2){
            $new_address->updateData(['wx_openid'=>$wx_openid,'address_default'=>2,'address_id'=>['neq',$address_id]],['address_default'=>1]);
        }
        $new_address->updateData(['address_id' => $address_id, 'wx_openid' => $wx_openid], $data);
        return $this->json('更新成功');
    }

    /**
     * @OpDescription(whatFor="删除",codeMonkey="")
     */
    public function deleteaddressOp(Request $request, Address $new_address)
    {
        $wx_openid  = $request['wx_openid'];
        $address_id = $request['address_id'];
        $address_count = $new_address->countByCondition(['wx_openid' => $wx_openid]);
        if ($address_count == 1) {
            return $this->json('需要保留一个地址', -1);
        }
        $update_count = $new_address->updateData(['address_id' => $address_id, 'wx_openid' => $wx_openid], ['datastatus' => 0]);
        if(empty($update_count)){
            throw new \Exception('删除失败',-1);
        }
        return $this->json('删除成功');
    }

}
