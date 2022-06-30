<?php

namespace App\Control;

use MrStock\System\Helper\RpcRequest;
use MrStock\System\MJC\Control;
use MrStock\System\MJC\Http\Request;
use MrStock\System\Helper\Arr;
use Common\Model\Goods;
use Common\Model\ShopCart;
use Common\Model\AreaPrice;
use MrStock\System\Helper\Config;
use Common\Libraries\AppTools;

/**
 * @OpDescription(whatFor="首页",codeMonkey="")
 */
class ShopcartControl extends Control
{
    public $middleware = [

    ];

    /**
     * @OpDescription(whatFor="加入购物车",codeMonkey="")
     */
    public function incdecshopcartOp(Request $request, ShopCart $new_shop_cart, Goods $new_goods)
    {
        $wx_openid   = $request['wx_openid'];
        $goods_id    = $request['goods_id'];
        $goods_count = $request['goods_count'];
        $goods_find  = $new_goods->findByCondition(['goods_id' => $goods_id, 'publish_status' => 2]);
        if (empty($goods_find)) {
            return $this->json('该商品已下架', -1);
        }
        $shop_cart_find = $new_shop_cart->findByCondition(['wx_openid' => $wx_openid, 'goods_id' => $goods_id]);
        if (!empty($shop_cart_find)) {
            if ($goods_count == 'inc') { //如果为1则加1
                $shop_cart_count = $new_shop_cart->setByConditionInc(['wx_openid' => $wx_openid, 'goods_id' => $goods_id]);
                if (empty($shop_cart_count)) {
                    return $this->json('更新失败', -1);
                }
            } elseif ($goods_count == 'dec') {
                if ($shop_cart_find['goods_count'] == 1) {
                    return $this->json('最少有一个商品', -1);
                }
                $shop_cart_count = $new_shop_cart->setByConditionDec(['wx_openid' => $wx_openid, 'goods_id' => $goods_id]);
                if (empty($shop_cart_count)) {
                    return $this->json('更新失败', -1);
                }
            } else {
                $shop_cart_count = $new_shop_cart->updateData(['wx_openid' => $wx_openid, 'goods_id' => $goods_id], ['goods_count' => $goods_count]);
                if (empty($shop_cart_count)) {
                    return $this->json('更新失败', -1);
                }
            }
            return $this->json('更新成功');
        } else {
            $data['wx_openid']   = $wx_openid;
            $data['goods_id']    = $goods_id;
            $data['goods_count'] = $goods_count;
            $new_shop_cart->insertData($data);
            return $this->json('加入成功');
        }
    }

    /**
     * @OpDescription(whatFor="购物车列表",codeMonkey="")
     */
    public function shopcartlistOp(Request $request, ShopCart $new_shop_cart)
    {
        $wx_openid     = $request['wx_openid'];
        $shop_cart_arr = $new_shop_cart->selectByShopcartGoods(['wx_openid' => $wx_openid], 'shop_cart_id,shop_cart.goods_id,cover_img,goods_name,goods_price,goods_count,min_buy_num');
        foreach($shop_cart_arr as $key=>&$value){
            $value['goods_price']  = sprintf("%.2f", $value['goods_price'] / 100);
        }
        $res['list']   = $shop_cart_arr;
        return $this->json($res);
    }

    /**
     * @OpDescription(whatFor="计算价格",codeMonkey="")
     */
    public function calculateOp(Request $request, ShopCart $new_shop_cart)
    {
        $shop_cart_ids = $request['shop_cart_ids'];
        $wx_openid     = $request['wx_openid'];
        $goods_arr     = $new_shop_cart->selectByShopcartGoods(['shop_cart_id' => ['in', $shop_cart_ids]], 'goods_price,goods_count,area_price,coupon_status');
        //发出地址请求
        $user_address_findbycondition = [
            'where' => json_encode(['wx_openid' => $wx_openid, 'address_default' => 2])
        ];
        RpcRequest::asyncRequest('user-address-findbycondition', $user_address_findbycondition);
        //结束
        //发出用户请求
        $user_user_findbycondition = [
            'wx_openid' => $wx_openid,
            'field'     => 'coupon_money'
        ];
        RpcRequest::asyncRequest('user-user-findbycondition', $user_user_findbycondition);
        //获取用户地址省份ID
        $address_info_response = RpcRequest::asyncResponse('user-address-findbycondition', $user_address_findbycondition);
        if ($address_info_response['code'] != 1) {
            return $this->json('依赖user-address-findbycondition:' . $address_info_response['message'], $address_info_response['code']);
        }
        $province_id = $address_info_response['data']['province_id'];
        //获取用户信息
        $user_info_response = $address_info_response = RpcRequest::asyncResponse('user-user-findbycondition', $user_user_findbycondition);
        if ($user_info_response['code'] != 1) {
            return $this->json('依赖user-user-findbycondition:' . $user_info_response['message'], $user_info_response['code']);
        }
        $coupon_money = $user_info_response['data']['coupon_money'];
        //组合
        foreach ($goods_arr as &$value) {
//            $value['goods_price']       = (int)$value['goods_price'];
            $value['goods_total_price'] = $value['goods_price'] * $value['goods_count'];
            //物流价格计算
            $area_price_arr            = json_decode($value['area_price'], true);
            $value['area_total_price'] = 0;
            foreach ($area_price_arr as $val) {
                if ($val['area_id'] == $province_id) {
                    $value['area_total_price'] = $val['area_price'] * $value['goods_count'];
                    break;
                }
            }
            //结束
            //优惠金额
            $value['coupon_total_price'] = 0;
            if ($goods_arr['coupon_status'] == 2) {
                if ($value['goods_total_price'] >= $coupon_money) { //商品总价>优惠券金额
                    $value['coupon_total_price'] = $coupon_money;
                    $coupon_money                = 0;
                } else {
                    $value['coupon_total_price'] = $value['goods_total_price'];
                    $coupon_money                = $coupon_money - $value['goods_total_price'];
                }
            }
        }
        $goods_total_price         = array_sum(array_column($goods_arr, 'goods_total_price'));
        $area_total_price          = array_sum(array_column($goods_arr, 'area_total_price'));
        $coupon_total_price        = array_sum(array_column($goods_arr, 'coupon_total_price'));
        $total_pay_money           = $goods_total_price + $area_total_price - $coupon_total_price;
        $res['goods_total_price']  = sprintf("%.2f", $goods_total_price / 100);
        $res['area_total_price']   = sprintf("%.2f",$area_total_price/100);
        $res['coupon_total_price'] = sprintf("%.2f",$coupon_total_price/100);
        $res['total_pay_money']    = sprintf("%.2f",$total_pay_money/100);
        return $this->json($res);
    }

    /**
     * @OpDescription(whatFor="批量删除",codeMonkey="")
     */
    public function deleteshopcartOp(Request $request, ShopCart $new_shop_cart)
    {
        $wx_openid       = $request['wx_openid'];
        $shop_cart_ids   = $request['shop_cart_ids'];
        $shop_cart_count = count(explode(',', $shop_cart_ids));
        $update_count    = $new_shop_cart->updateData(['wx_openid' => $wx_openid, 'shop_cart_id' => ['in', $shop_cart_ids]], ['datastatus' => 0]);
        if ($update_count != $shop_cart_count) {
            return $this->json('删除失败', -1);
        }
        return $this->json('删除成功', 1);
    }

}
