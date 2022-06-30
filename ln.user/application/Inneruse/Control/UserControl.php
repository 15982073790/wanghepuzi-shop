<?php

namespace Inneruse\Control;


use MrStock\System\MJC\Control;
use MrStock\System\MJC\Http\Request;
use MrStock\System\Helper\Arr;
use Common\Model\User;
use Common\Logic\UserLogic;
use MrStock\System\Helper\Config;
use Common\Libraries\AppTools;

/**
 * @@OpDescription(whatFor="用户列表",codeMonkey="")
 */
class UserControl extends Control
{
    public $middleware = [
    ];

    /**
     * @@OpDescription(whatFor="根据条件查询用户信息",codeMonkey="")
     */
    public function findbyconditionOp(Request $request, User $new_user)
    {
        $wx_openid = $request['wx_openid'];
        $where = $request['where'];
        $field     = $request['field'];
        if(!empty($wx_openid)){
            $res       = $new_user->findByCondition(['wx_openid' => $wx_openid], $field);
        }else{
            $where     = json_decode($where,true);
            $res       = $new_user->findByCondition($where, $field);
        }
        return $this->json($res);
    }
    /**
     * @@OpDescription(whatFor="根据条件批量查询用户信息",codeMonkey="")
     */
    public function selectbyconditionOp(Request $request, User $new_user)
    {
        $where     = $request['where'];
        $field     = $request['field'];
        $res       = $new_user->selectByCondition(json_decode($where,true), $field);

        return $this->json($res);
    }

    /**
     * @@OpDescription(whatFor="根据条件更新用户信息",codeMonkey="")
     */
    public function updatedataOp(Request $request, User $new_user)
    {
        $where = json_decode($request['where'], true);
        $data  = json_decode($request['data'], true);
        $res   = $new_user->updateData($where, $data);
        return $this->json($res);
    }
    /**
     * @@OpDescription(whatFor="订单过期或被取消优惠券退回",codeMonkey="")
     */
    public function backcouponOp(Request $request, User $new_user)
    {
        $wx_openid          = $request['wx_openid'];
        $total_coupon_price = $request['total_coupon_price'];
        $new_user->setIncByCondition(['wx_openid' => $wx_openid], 'coupon_money', $total_coupon_price);
        return $this->json('退回优惠券成功', 1);
    }
    /**
     * @@OpDescription(whatFor="新增用户信息",codeMonkey="")
     */
    public function insertdataOp(Request $request, User $new_user){
        $data  = json_decode($request['data'], true);
        $res   = $new_user->insertData($data);
        return $this->json($res);
    }
}
