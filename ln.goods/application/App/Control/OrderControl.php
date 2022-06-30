<?php

namespace App\Control;

use MrStock\System\Helper\RpcRequest;
use MrStock\System\MJC\Control;
use MrStock\System\MJC\Http\Request;
use MrStock\System\Helper\Arr;
use Common\Model\Goods;
use Common\Model\Order;
use Common\Model\OrderGoods;
use MrStock\System\Helper\Config;
use Common\Libraries\AppTools;
use Common\Logic\OrderLogic;

/**
 * @OpDescription(whatFor="订单",codeMonkey="")
 */
class OrderControl extends Control
{
    public $middleware = [
        'updateorderstatusOp'    => [
            'Common\Middleware\Orderupdateorderstatus'
        ],
    ];

    /**
     * @@OpDescription(whatFor="创建订单",codeMonkey="")
     */
    public function createorderOp(Request $request, Goods $new_goods, Order $new_order, OrderGoods $new_order_goods)
    {
        $wx_openid = $request['wx_openid'];
        //商品清单
        $goods_list = json_decode($request['goods_list'], true); //goods_id goods_count
        if (count($goods_list) > 1) {
            return $this->json('只能选择1种商品下单', -1);
        }
        //商品信息
        $goods_id_arr = array_column($goods_list, 'goods_id');
        $goods_arr    = $new_goods->selectByCondition(['goods_id' => ['in', $goods_id_arr]]);
        $goods_arr    = Arr::arrayToArrayKey($goods_arr, 'goods_id');
        //结束
        //库存判断
        foreach ($goods_list as $key => $value) {
            foreach ($goods_arr as $k => $val) {
                $residue = $val['repertory_num'] - $val['buy_num'];
                if ($value['goods_id'] == $val['goods_id'] && $value['goods_count'] > $residue) {
                    return $this->json("《{$val['goods_name']}》库存为{$residue},请重新选择购买数量", -1);
                }
            }
        }
        //结束
        //最少购买量判断
        foreach ($goods_list as $key => $value) {
            foreach ($goods_arr as $k => $val) {
                if ($value['goods_id'] == $val['goods_id'] && $value['goods_count'] < $val['min_buy_num']) {
                    return $this->json("《{$val['goods_name']}》最少购买{$val['min_buy_num']}个", -1);
                }
            }
        }
        //结束
        //发出地址请求
        $user_address_findbycondition = [
            'where' => json_encode(['wx_openid' => $wx_openid]),
            'other_where' => json_encode(['order'=>'address_default desc,address_id desc'])
        ];
        RpcRequest::asyncRequest('user-address-findbycondition', $user_address_findbycondition);
        //结束
        //发出用户请求
        $user_user_findbycondition = [
            'where' => json_encode(['wx_openid'=>$wx_openid,'expire_time'=>['gt',time()]]),
            'field'     => 'coupon_money'
        ];
        RpcRequest::asyncRequest('user-user-findbycondition', $user_user_findbycondition);
        //结束
        //获取用户地址省份ID
        $address_info_response = RpcRequest::asyncResponse('user-address-findbycondition', $user_address_findbycondition);
        if ( $address_info_response['code'] != 1 ) {
            return $this->json('依赖user-address-findbycondition:' . $address_info_response['message'], $address_info_response['code']);
        }
//        $address_id  = $address_info_response['data']['address_id'];
        if(empty($address_info_response['data']['province_id'])){
            return $this->json("请完善收货地址", -1);
        }
        foreach ($goods_arr as $key=>$value) {
            $area_price = json_decode($value['area_price'],true);
            $area_id_arr = array_column($area_price,'area_id');
            if(!empty($area_id_arr) && !in_array($address_info_response['data']['province_id'],$area_id_arr)){
                return $this->json("《{$value['goods_name']}》不支持该默认地址填写的省份,请联系客服", -1);
            }
        }
        $province_id    = $address_info_response['data']['province_id'];
        $province_name  = $address_info_response['data']['province_name'];
        $city_name      = $address_info_response['data']['city_name'];
        $county_name    = $address_info_response['data']['county_name'];
        $detail_address = $address_info_response['data']['detail_address'];
        $true_name      = $address_info_response['data']['true_name'];
        $address_tel    = $address_info_response['data']['address_tel'];
        $address_info   = trim("$province_name $city_name $county_name $detail_address $true_name $address_tel");
        //获取用户信息
        $user_info_response = $address_info_response = RpcRequest::asyncResponse('user-user-findbycondition', $user_user_findbycondition);
        if ( $user_info_response['code'] != 1 ) {
            return $this->json('依赖user-user-findbycondition:' . $user_info_response['message'], $user_info_response['code']);
        }
        $coupon_money = $user_info_response['data']['coupon_money'];
        //组合
        foreach ( $goods_list as &$value ) {
            $value['goods_price']       = (int)$goods_arr[$value['goods_id']]['goods_price'];
            $value['goods_total_price'] = $goods_arr[$value['goods_id']]['goods_price'] * $value['goods_count'];
            //物流价格计算
            $area_price_arr            = json_decode($goods_arr[$value['goods_id']]['area_price'], true);
            $value['area_total_price'] = 0;
            foreach ( $area_price_arr as $val ) {
                if ( $val['area_id'] == $province_id ) {
                    $value['area_total_price'] = $val['area_price'] * $value['goods_count'];
                    break;
                }
            }
            //结束
            //优惠金额
            $value['coupon_total_price'] = 0;
            if ( $goods_arr[$value['goods_id']]['coupon_status'] == 2 ) {
                if ( $value['goods_total_price'] >= $coupon_money ) { //商品总价>优惠券金额
                    $value['coupon_total_price'] = (int)$coupon_money;
                    $coupon_money                = 0;
                } else {
                    $value['coupon_total_price'] = $value['goods_total_price'];
                    $coupon_money                = $coupon_money - $value['goods_total_price'];
                }
            }
            $new_goods->setIncByCondition(['goods_id' => $value['goods_id']], 'buy_num', $value['goods_count']);//商品购买数加1
        }
        $total_goods_price  = array_sum(array_column($goods_list, 'goods_total_price'));
        $total_area_price   = array_sum(array_column($goods_list, 'area_total_price'));
        $total_coupon_price = array_sum(array_column($goods_list, 'coupon_total_price'));
        $user_response      = RpcRequest::syncRequest('user-user-updatedata', ['where' => json_encode(['wx_openid' => $wx_openid]), 'data' => ['coupon_money' => $coupon_money]]);
        if ( $user_response['code'] != 1 ) {
            throw new \Exception('依赖user-user-updatedata:' . $user_response['message'], $user_response['code']);
        }
        $data['wx_openid']          = $wx_openid;
        $data['address']            = $address_info;
        $data['total_goods_price']  = $total_goods_price;
        $data['total_area_price']   = $total_area_price;
        $data['total_coupon_price'] = $total_coupon_price;
        $data['total_pay_money']    = $total_goods_price + $total_area_price - $total_coupon_price;
        $data['order_status']       = 1;
        $data['order_sn']           = date('YmdHis').substr(explode(' ',microtime())[0],2,6);//截取时间戳
        $new_order->beginTransaction();
        $order_id = $new_order->insertData($data);
        foreach ( $goods_list as &$value ) {
            $value['order_id'] = $order_id;
        }
        $new_order_goods->insertAllData($goods_list);
        $new_order->commit();
        $res['order_id'] = $order_id;
        return $this->json($res);
    }

    /**
     * @@OpDescription(whatFor="订单列表",codeMonkey="")
     */
    public function orderlistOp(Request $request, Order $new_order, OrderGoods $new_order_goods)
    {
        $wx_openid          = $request['wx_openid'];
        $order_status       = $request['order_status']; //多个用逗号隔开
        $where['wx_openid'] = $wx_openid;
        $where['order_delete'] = 1;
        if ( !empty($order_status) ) {
            $where['order_status'] = ['in',$order_status];
        }
        $order_arr       = $new_order->selectByCondition($where);
        $order_arr       = Arr::arrayToArrayKey($order_arr, 'order_id');
        $order_id_arr    = array_column($order_arr, 'order_id');
        $order_goods_arr = $new_order_goods->selectByOrdergoodsGoods(['order_id' => ['in', $order_id_arr]], ['order'=>'order_id desc'], 'order_id,order_goods.goods_id,goods_name,cover_img,goods_count,order_goods.goods_price,model,specification');
        $order_goods_arr = Arr::arrayToArrayKey($order_goods_arr, 'order_id', 1);
        $i               = 0;
        $order_goods_res = [];
        foreach ( $order_goods_arr as $key => $value ) {
            $order_goods_res[$i]['order_id']        = $key;
//            $order_goods_res[$i]['cover_img']       = $value[0]['cover_img'];
            $order_goods_res[$i]['total_pay_money'] = sprintf("%.2f", $order_arr[$key]['total_pay_money']/100);
            $order_goods_res[$i]['order_status']    = $order_arr[$key]['order_status'];
            switch ( $order_goods_res[$i]['order_status'] ) {
                case 1:
                    $order_goods_res[$i]['order_status_name'] = '待支付';
                    $order_goods_res[$i]['expire_status'] = $order_arr[$key]['expire_status']; //未过期
                    break;
                case -1:
                    $order_goods_res[$i]['order_status_name'] = '已取消';
                    break;
                case 2:
                    $order_goods_res[$i]['order_status_name'] = '已支付';
                    break;
                case -3:
                    $order_goods_res[$i]['order_status_name'] = '退款中';
                    break;
                case 3:
                    $order_goods_res[$i]['order_status_name'] = '已退款';
                    break;
                case 4:
                    $order_goods_res[$i]['order_status_name'] = '已发货';
                    break;
                case 5:
                    $order_goods_res[$i]['order_status_name'] = '已完成';
                    break;
                default:
                    $order_goods_res[$i]['order_status_name'] = '';
                    break;
            }
            $order_goods_res[$i]['create_time']     = $order_arr[$key]['itime'];
            foreach ( $value as $k => $val ) {
                $order_goods_res[$i]['goods_info'][$k]['goods_id']      = $val['goods_id'];
                $order_goods_res[$i]['goods_info'][$k]['goods_name']    = $val['goods_name'];
                $order_goods_res[$i]['goods_info'][$k]['goods_count']   = $val['goods_count'];
                $order_goods_res[$i]['goods_info'][$k]['cover_img']     = $val['cover_img'];
                $order_goods_res[$i]['goods_info'][$k]['goods_price']   = sprintf("%.2f", $val['goods_price']/100);
                $order_goods_res[$i]['goods_info'][$k]['model']         = $val['model'];
                $order_goods_res[$i]['goods_info'][$k]['specification'] = $val['specification'];
            }
            ++$i;
        }
        $res = $order_goods_res;
        return $this->json($res);

    }

    /**
     * @@OpDescription(whatFor="详情",codeMonkey="")
     */
    public function detailOp(Request $request, Goods $new_goods, Order $new_order, OrderGoods $new_order_goods)
    {
        $order_id                         = $request['order_id'];
        $order_find                       = $new_order->findByCondition(['order_id' => $order_id, 'order_delete' => 1]);
        $order_find['total_goods_price']  = sprintf("%.2f", $order_find['total_goods_price'] / 100);
        $order_find['total_area_price']   = sprintf("%.2f", $order_find['total_area_price'] / 100);
        $order_find['total_coupon_price'] = sprintf("%.2f", $order_find['total_coupon_price'] / 100);
        $order_find['total_pay_money']    = sprintf("%.2f", $order_find['total_pay_money'] / 100);
        switch ( $order_find['order_status'] ) {
            case 1:
                $order_find['order_status_name'] = '待支付';
                break;
            case -1:
                $order_find['order_status_name'] = '已取消';
                break;
            case 2:
                $order_find['order_status_name'] = '已支付';
                break;
            case -3:
                $order_find['order_status_name'] = '退款中';
                break;
            case 3:
                $order_find['order_status_name'] = '已退款';
                break;
            case 4:
                $order_find['order_status_name'] = '已发货';
                break;
            case 5:
                $order_find['order_status_name'] = '已完成';
                break;
            default:
                $order_find['order_status_name'] = '';
                break;
        }
        unset(
            $order_find['utime'],
            $order_find['dtime'],
            $order_find['datastatus']
        );
        $order_goods_arr           = $new_order_goods->selectByOrdergoodsGoods(
            ['order_id' => $order_id],
            [],
            'order_goods.goods_id,goods_name,goods_count,cover_img,order_goods.goods_price,goods_total_price,area_total_price,coupon_total_price,model,specification');
        foreach ($order_goods_arr as $key => &$value) {
            $value['goods_price']        = sprintf("%.2f", $value['goods_price'] / 100);
            $value['goods_total_price']  = sprintf("%.2f", $value['goods_total_price'] / 100);
            $value['area_total_price']   = sprintf("%.2f", $value['area_total_price'] / 100);
            $value['coupon_total_price'] = sprintf("%.2f", $value['coupon_total_price'] / 100);
        }
        $order_find['order_goods'] = $order_goods_arr;
        return $this->json($order_find);

    }

    /**
     * @@OpDescription(whatFor="订单更新地址",codeMonkey="")
     */
    public function updateaddressOp(Request $request, Order $new_order, Goods $new_goods, OrderGoods $new_order_goods)
    {
        $order_id   = $request['order_id'];
        $address_id = $request['address_id'];
        //获取用户地址ID对应的地址信息
        $user_address_findbycondition = [
            'where' => json_encode(['address_id' => $address_id])
        ];
        $address_info_response        = RpcRequest::syncRequest('user-address-findbycondition', $user_address_findbycondition);
        if ( $address_info_response['code'] != 1 ) {
            return $this->json('依赖user-address-findbycondition:' . $address_info_response['message'], $address_info_response['code']);
        }
        //结束
        $province_id    = $address_info_response['data']['province_id'];
        $province_name  = $address_info_response['data']['province_name'];
        $city_name      = $address_info_response['data']['city_name'];
        $county_name    = $address_info_response['data']['county_name'];
        $detail_address = $address_info_response['data']['detail_address'];
        $true_name      = $address_info_response['data']['true_name'];
        $address_tel    = $address_info_response['data']['address_tel'];
        $address_info   = trim("$province_name $city_name $county_name $detail_address $true_name $address_tel");
        $new_order_goods->beginTransaction();
        //商品信息
        $order_goods_arr = $new_order_goods->selectByOrdergoodsGoods(
            ['order_id' => $order_id], [], 'order_goods.goods_id,goods_name,order_goods.goods_count,area_price'
        );
        //组合
        foreach ( $order_goods_arr as &$value ) {
            //物流价格计算
            $area_price_arr            = json_decode($value['area_price'], true);
            $value['area_total_price'] = 0;
            $area_id_arr = array_column($area_price_arr,'area_id');
            if(!in_array($province_id,$area_id_arr)){
                return $this->json("《{$value['goods_name']}》不支持该省份,请联系客服", -1);
            }
            foreach ( $area_price_arr as $val ) {
                if ( $val['area_id'] == $province_id ) {
                    $value['area_total_price'] = $val['area_price'] * $value['goods_count'];
                    $new_order_goods->updateData(['order_id' => $order_id, 'goods_id' => $value['goods_id']], ['area_total_price' => $value['area_total_price']]);
                    break;
                }
            }
        }
        $order_find         = $new_order->findByCondition(['order_id' => $order_id]);
        $total_goods_price  = $order_find['total_goods_price'];
        $total_area_price   = array_sum(array_column($order_goods_arr, 'area_total_price'));
        $total_coupon_price = $order_find['total_coupon_price'];
        $total_pay_money    = $total_goods_price + $total_area_price - $total_coupon_price;
        $update_count       = $new_order->updateData(['order_id' => $order_id], ['address' => $address_info, 'total_area_price' => $total_area_price, 'total_pay_money' => $total_pay_money]);
        if ( empty($update_count) ) {
            throw new \Exception('更新失败', -1);
        }
        $new_order_goods->commit();
        $res['total_area_price'] = sprintf("%.2f", $total_area_price / 100);
        $res['total_pay_money']  = sprintf("%.2f", $total_pay_money / 100);
        return $this->json($res);
    }

    /**
     * @@OpDescription(whatFor="更新订单状态",codeMonkey="")
     */
    public function updateorderstatusOp(Request $request, Order $new_order,OrderGoods $new_order_goods,Goods $new_goods)
    {
        $order_id     = $request['order_id'];
        $order_status = $request['order_status'];

        if ($order_status == -1) {
            //取消订单则退回优惠券
            $order_find         = $new_order->findByCondition(['order_id' => $order_id]);
            $user_info_response = RpcRequest::syncRequest('user-user-backcoupon', ['wx_openid' => $order_find['wx_openid'], 'total_coupon_price' => $order_find['total_coupon_price']]);
            if ($user_info_response['code'] != 1) {
                return $this->json('依赖user-user-backcoupon:' . $user_info_response['message'], $user_info_response['code']);
            }
            //购买后减去商品数
            $order_goods_arr = $new_order_goods->selectByCondition(['order_id' => $order_id]);
            foreach ($order_goods_arr as $key => $value) {
                $new_goods->setDecByCondition(['goods_id' => $value['goods_id']], 'buy_num', $value['goods_count']);
            }
        }
        //结束
        if($order_status==-2){ //如果为删除则更新order_delete字段
            $update_count = $new_order->updateData(['order_id' => $order_id], ['order_delete' => 2]);
        }else{
            $update_count = $new_order->updateData(['order_id' => $order_id], ['order_status' => $order_status]);
        }
        if (empty($update_count)) {
            return $this->json('更新失败', -1);
        }
        return $this->json('更新成功');
    }
    /**
     * @@OpDescription(whatFor="查询物流",codeMonkey="")
     */
    public function querywaybillOp(Request $request)
    {
        $order_id =$request['order_id'];
        $new_order_logic = new OrderLogic();
        $res = $new_order_logic->querywaybill($order_id);
        return $this->json($res);
    }
    /**
     * @@OpDescription(whatFor="各订单状态数量",codeMonkey="")
     */
    public function orderstatusnumOp(Request $request,Order $new_order){
        $wx_openid                  = $request['wx_openid'];
        $order_arr                  = $new_order->selectByCondition(['wx_openid' => $wx_openid]);
        $res['wait_pay_num']        = 0;
        $res['cancel_pay_num']      = 0;
        $res['complete_pay_num']    = 0;
        $res['wait_refund_num']     = 0;
        $res['complete_refund_num'] = 0;
        $res['send_goods_num']      = 0;
        $res['complete_goods_num']  = 0;
        foreach ($order_arr as $key => $value) {
            switch ($value['order_status']) {
                case 1:
                    ++$res['wait_pay_num'];
                    break;
                case -1:
                    ++$res['cancel_pay_num'];
                    break;
                case 2:
                    ++$res['complete_pay_num'];
                    break;
                case -3:
                    ++$res['wait_refund_num'];
                    break;
                case 3:
                    ++$res['complete_refund_num'];
                    break;
                case 4:
                    ++$res['send_goods_num'];
                    break;
                case 5:
                    ++$res['complete_goods_num'];
                    break;
            }
        }
        return $this->json($res);
    }

}
