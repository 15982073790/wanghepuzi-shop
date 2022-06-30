<?php

namespace Crontab\Control;

use MrStock\System\Helper\RpcRequest;
use MrStock\System\MJC\Control;
use MrStock\System\MJC\Http\Request;
use MrStock\System\Helper\Arr;
use Common\Model\Order;
use Common\Model\OrderGoods;
use Common\Model\Goods;
use MrStock\System\Helper\Config;
use Common\Libraries\AppTools;

/**
 * @OpDescription(whatFor="订单",codeMonkey="")
 */
class OrderControl extends Control
{
    public $middleware = [
    ];
    /**
     * @OpDescription(whatFor="待支付订单设置过期",codeMonkey="")
     */
    public function orderexpireOp(Order $new_order,OrderGoods $new_order_goods,Goods $new_goods){
        $expire_time = time()-3600*12;
        //购买后减去商品数
        $order_arr      = $new_order->selectByCondition(['order_status'=>1,'expire_status'=>1,'itime'=>['lt',$expire_time]]);
        //购买数恢复至未买前
        $order_id_arr  = array_column($order_arr,'order_id');
        $order_goods_arr = $new_order_goods->selectByCondition(['order_id' => ['in',$order_id_arr]]);
        foreach ($order_goods_arr as $key => $value) {
            $new_goods->setDecByCondition(['goods_id' => $value['goods_id']], 'buy_num', $value['goods_count']);
        }
        //结束
        //优惠券退回
        foreach($order_arr as $key=>$value){
            $user_info_response = RpcRequest::syncRequest('user-user-backcoupon',['wx_openid'=>$value['wx_openid'],'total_coupon_price'=>$value['total_coupon_price']]);
            if ( $user_info_response['code'] != 1 ) {
                return $this->json('依赖user-user-backcoupon:' . $user_info_response['message'], $user_info_response['code']);
            }
        }
        //结束
        $new_order->updateData(['order_id' => ['in',$order_id_arr]],['expire_status'=>2]);
        return $this->json('设置成功');
    }

    /**
     * @OpDescription(whatFor="14天后设置为已完成",codeMonkey="")
     */
    public function ordercompleteOp(Order $new_order, OrderGoods $new_order_goods, Goods $new_goods)
    {
        $complete_time = time() - 3600 * 24 * 14;
        $new_order->updateData([
            'send_time'    => [
                ['neq', 0],
                ['lt', $complete_time]
            ],
            'order_status' => 4
        ],
            ['order_status' => 5]);
        return $this->json('设置成功');
    }
}
