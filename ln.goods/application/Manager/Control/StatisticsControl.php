<?php

namespace Manager\Control;

use MrStock\System\Helper\RpcRequest;
use MrStock\System\MJC\Control;
use MrStock\System\MJC\Http\Request;
use MrStock\System\Helper\Arr;
use Common\Model\Goods;
use Common\Model\Order;
use Common\Model\OrderGoods;
use MrStock\System\Helper\Config;
use Common\Libraries\AppTools;

/**
 * @OpDescription(whatFor="财务统计",codeMonkey="")
 */
class StatisticsControl extends Control
{
    public $middleware = [

    ];

    /**
     * @@OpDescription(whatFor="财务列表",codeMonkey="")
     */
    public function indexOp(Request $request, Goods $new_goods, Order $new_order, OrderGoods $new_order_goods)
    {
        $request['curpage']  = $request['curpage'] ?: 1;
        $request['pagesize'] = $request['pagesize'] ?: 10;
        $order_status = $request['order_status'];
        if(!empty($order_status)){
            $order_arr = $new_order->selectByCondition(['order_status'=>$order_status]);
            if(empty($order_arr)){
                $res = AppTools::mpage([],$request['curpage'],$request['pagesize']);
                return $this->json($res);
            }
            $order_id_arr = array_column($order_arr,'order_id');
            $request['order_id']=['in',$order_id_arr];
        }
        $order_goods_arr = $new_order_goods->selectByOrdergoodsGoods($request,[],'order_id,order_goods.goods_id,goods_name,goods_total_price,area_total_price,coupon_total_price');
        //订单状态
        $order_goods_id_arr = array_column($order_goods_arr,'order_id');
        $order_arr = $new_order->selectByCondition(['order_id'=>['in',$order_goods_id_arr]]);
        $order_arr = Arr::arrayToArrayKey($order_arr,'order_id');
        //结束
        $order_goods_arr = Arr::arrayToArrayKey($order_goods_arr,'goods_name',1);
        $i=0;
        foreach($order_goods_arr as $key=>&$value) {
            $order_goods_res[$i]['goods_name'] = $key;
            foreach ($value as $k => &$val) {
                switch ($order_arr[$val['order_id']]['order_status']) {
                    case 1;
                        $order_goods_res[$i]['wait_pay'] += $val['goods_total_price'] + $val['area_total_price'] - $val['coupon_total_price'];
                        break;
                    case -1;
                        $order_goods_res[$i]['cancel_pay'] += $val['goods_total_price'] + $val['area_total_price'] - $val['coupon_total_price'];
                        break;
                    case 2;
                        $order_goods_res[$i]['complete_pay'] += $val['goods_total_price'] + $val['area_total_price'] - $val['coupon_total_price'];
                        break;
                    case -3;
                        $order_goods_res[$i]['wait_refund'] += $val['goods_total_price'] + $val['area_total_price'] - $val['coupon_total_price'];
                        break;
                    case 3;
                        $order_goods_res[$i]['complete_refund'] += $val['goods_total_price'] + $val['area_total_price'] - $val['coupon_total_price'];
                        break;
                    case 4;
                        $order_goods_res[$i]['send_goods'] += $val['goods_total_price'] + $val['area_total_price'] - $val['coupon_total_price'];
                        break;
                    case 5;
                        $order_goods_res[$i]['complete_goods'] += $val['goods_total_price'] + $val['area_total_price'] - $val['coupon_total_price'];
                        break;

                }
            }
            $order_goods_res[$i]['total_money'] = $order_goods_res[$i]['complete_pay'] + $order_goods_res[$i]['wait_refund'] + $order_goods_res[$i]['send_goods'] +$order_goods_res[$i]['complete_goods'];
            ++$i;
        }
        foreach ($order_goods_res as &$value) {
            $value['wait_pay']        = sprintf("%.2f", $value['wait_pay'] / 100);
            $value['cancel_pay']      = sprintf("%.2f", $value['cancel_pay'] / 100);
            $value['complete_pay']    = sprintf("%.2f", $value['complete_pay'] / 100);
            $value['wait_refund']     = sprintf("%.2f", $value['wait_refund'] / 100);
            $value['complete_refund'] = sprintf("%.2f", $value['complete_refund'] / 100);
            $value['send_goods']      = sprintf("%.2f", $value['send_goods'] / 100);
            $value['complete_goods']  = sprintf("%.2f", $value['complete_goods'] / 100);
            $value['total_money']  = sprintf("%.2f", $value['total_money'] / 100);
        }
        $res = AppTools::mpage($order_goods_res,$request['curpage'],$request['pagesize']);
        return $this->json($res);
    }


}
