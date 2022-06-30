<?php

namespace Manager\Control;


use MrStock\System\MJC\Control;
use MrStock\System\MJC\Http\Request;
use MrStock\System\Helper\Arr;
use Common\Model\User;
use Common\Model\Area;
use Common\Logic\UserLogic;
use MrStock\System\Helper\Config;
use Common\Libraries\AppTools;

/**
 * @ControlDescription(menuName="用户列表",cGroupName="用户")
 */
class UserControl extends Control
{
    public $middleware = [
        'control'      => [],
        'addinfoOp'    => [
            'Common\Middleware\Useraddinfo'
        ],
        'updateinfoOp' => [
            'Common\Middleware\Userupdateinfo'
        ],
        'startopendelOp' => [
            'Common\Middleware\Userstartstopdel'
        ]
    ];

    /**
     * @OpDescription(whatFor="用户列表",menuName="用户列表",codeMonkey="")
     */
    public function indexOp(Request $request, User $new_user, Area $new_area)
    {
        $request['curpage']  = $request['curpage'] ?: 1;
        $request['pagesize'] = $request['pagesize'] ?: 10;
        $list                = $new_user->selectByCurpage($request);
        $count               = $new_user->countByCurpage($request);
        //组合区域
        $province_id_arr  = array_column($list, 'province_id');
        $city_id          = array_column($list, 'city_id');
        $county_id        = array_column($list, 'county_id');
        $area_id_arr      = array_unique(array_merge($province_id_arr, $city_id, $county_id));
        $where['area_id'] = ['in', $area_id_arr];
        $area_arr         = $new_area->selectByCondition($where, 'area_id,area_name');
        $area_arr         = Arr::arrayToArrayKey($area_arr, 'area_id');
        foreach ($list as $key => &$value) {
            $value['province_name'] = $area_arr[$value['province_id']]['area_name'];
            $value['city_name']     = $area_arr[$value['city_id']]['area_name'];
            $value['county_name']   = $area_arr[$value['county_id']]['area_name'];
            $value['coupon_money'] = sprintf("%.2f", $value['coupon_money'] / 100);
        }
        $res = AppTools::mmorePage($list, $count, $request['curpage'], $request['pagesize']);
        return $this->json($res);
    }

    /**
     * @OpDescription(whatFor="区域",menuName="",codeMonkey="")
     */
    public function arealistOp(Request $request, Area $new_area)
    {
        $area_id     = $request['area_id'];
        $res['list'] = $new_area->selectByCondition(['pid' => $area_id], 'area_id,area_name,pid');
        return $this->json($res);
    }

    /**
     * @OpDescription(whatFor="新增",menuName="新增",codeMonkey="")
     */
    public function addinfoOp(Request $request, User $new_user)
    {
        $user_name    = $request['user_name'];
        $tel          = $request['tel'];
        $province_id  = $request['province_id'];
        $city_id      = $request['city_id'];
        $county_id    = $request['county_id'];
        $coupon_money = $request['coupon_money']*100;
        $expire_time  = $request['expire_time'];
        $user_find    = $new_user->findByCondition(['user_name' => $user_name]);
        if (!empty($user_find)) {
            return $this->json('该用户名已存在', -1);
        }
        $user_find = $new_user->findByCondition(['tel' => $tel]);
        if (!empty($user_find)) {
            return $this->json('该手机号已存在', -1);
        }
        $data['user_name']    = $user_name;
        $data['tel']          = $tel;
        $data['province_id']  = $province_id;
        $data['city_id']      = $city_id;
        $data['county_id']    = $county_id;
        $data['coupon_money'] = $coupon_money;
        $data['expire_time']  = $expire_time;
        $new_user->insertData($data);
        return $this->json('新增成功');
    }

    /**
     * @OpDescription(whatFor="编辑",menuName="",codeMonkey="")
     */
    public function editinfoOp(Request $request, User $new_user, Area $new_area)
    {
        $user_id                    = $request['user_id'];
        $user_find                  = $new_user->findByCondition(['user_id' => $user_id]);
        $user_find['coupon_money']  = sprintf("%.2f", $user_find['coupon_money'] / 100);
        $area_arr                   = array_merge((array)$user_find['province_id'], (array)$user_find['city_id'], (array)$user_find['county_id']);
        $area_arr                   = $new_area->selectByCondition(['area_id' => ['in', $area_arr]], 'area_id,area_name');
        $area_arr                   = Arr::arrayToArrayKey($area_arr, 'area_id');
        $user_find['province_name'] = $area_arr[$user_find['province_id']]['area_name'];
        $user_find['city_name']     = $area_arr[$user_find['city_id']]['area_name'];
        $user_find['county_name']   = $area_arr[$user_find['county_id']]['area_name'];
        unset(
            $user_find['itime'],
            $user_find['utime'],
            $user_find['dtime'],
            $user_find['datastatus']
        );
        $res = $user_find;
        return $this->json($res);
    }

    /**
     * @OpDescription(whatFor="更新",menuName="",codeMonkey="")
     */
    public function updateinfoOp(Request $request, User $new_user)
    {
        $user_id      = $request['user_id'];
        $user_name    = $request['user_name'];
        $tel          = $request['tel'];
        $province_id  = $request['province_id'];
        $city_id      = $request['city_id'];
        $county_id    = $request['county_id'];
        $coupon_money = $request['coupon_money']*100;
        $expire_time  = $request['expire_time'];
        $user_find    = $new_user->findByCondition(['user_id' => ['neq', $user_id], 'user_name' => $user_name]);
        if (!empty($user_find)) {
            return $this->json('该用户名已存在', -1);
        }
        $user_find = $new_user->findByCondition(['user_id' => ['neq', $user_id], 'tel' => $tel]);
        if (!empty($user_find)) {
            return $this->json('该手机号已存在', -1);
        }
        $data['user_name']    = $user_name;
        $data['tel']          = $tel;
        $data['province_id']  = $province_id;
        $data['city_id']      = $city_id;
        $data['county_id']    = $county_id;
        $data['coupon_money'] = $coupon_money;
        $data['expire_time']  = $expire_time;
        $new_user->updateData(['user_id' => $user_id], $data);
        return $this->json('更新成功');
    }
    /**
     * @OpDescription(whatFor="启用禁用",menuName="",codeMonkey="")
     */
    public function startopendelOp(Request $request, User $new_user){
        $user_id = $request['user_id'];
        $datastatus = $request['datastatus'];
        $update_count = $new_user->updateData(['user_id'=>$user_id],['datastatus'=>$datastatus]);
        if(empty($update_count)){
            return $this->json('禁用/启用/删除失败', -1);
        }
        return $this->json('禁用/启用/删除成功', 1);
    }
}
