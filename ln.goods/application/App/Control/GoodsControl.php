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
class GoodsControl extends Control
{
    public $middleware = [

    ];
    
    /**
     * @@OpDescription(whatFor="首页",codeMonkey="")
     */
    public function indexOp(Request $request, Goods $new_goods)
    {
        $wx_openid     = $request['wx_openid'];
        $user_response = RpcRequest::syncRequest('user-user-findbycondition', ['wx_openid' => $wx_openid, 'field' => 'tel']);
        if ($user_response['code'] != 1) {
            return $this->json('依赖user-user-findbycondition:' . $user_response['message'], $user_response['code']);
        }
        $goods_arr     = $new_goods->selectByCondition(['publish_status' => 2]);
        unset(
            $goods_arr['itime'],
            $goods_arr['utime'],
            $goods_arr['dtime'],
            $goods_arr['datastatus']
        );
        $banner_goods  = [];
        $quality_goods = [];
        $common_goods  = [];
        foreach ($goods_arr as $key => $value) {
            $value['goods_price']          = sprintf("%.2f", $value['goods_price'] / 100);
            $value['goods_original_price'] = sprintf("%.2f", $value['goods_original_price'] / 100);
            $value['fake_buy_num']         += $value['buy_num'];
            if (empty($value['preview_tel'])) {
                if ($value['banner_status'] == 2) {
                    $banner_goods[] = $value;
                } elseif ($value['quality_goods_status'] == 2) {
                    $quality_goods[] = $value;
                } else {
                    $common_goods[] = $value;
                }
            } elseif ($value['preview_tel'] == $user_response['data']['tel']) {
                if ($value['banner_status'] == 2) {
                    $banner_goods[] = $value;
                } elseif ($value['quality_goods_status'] == 2) {
                    $quality_goods[] = $value;
                } else {
                    $common_goods[] = $value;
                }
            }
        }
        $res['banner_goods']  = $banner_goods;
        $res['quality_goods'] = $quality_goods;
        $res['common_goods']  = $common_goods;
        return $this->json($res);
    }

    /**
     * @@OpDescription(whatFor="详情",codeMonkey="")
     */
    public function detailOp(Request $request, Goods $new_goods)
    {
        $goods_id                           = $request['goods_id'];
        $goods_find                         = $new_goods->findByCondition(['goods_id' => $goods_id]);
        $goods_find['goods_price']          = sprintf("%.2f", $goods_find['goods_price'] / 100);
        $goods_find['goods_original_price'] = sprintf("%.2f", $goods_find['goods_original_price'] / 100);
        $goods_find['fake_buy_num']         += $goods_find['buy_num'];
        unset(
            $goods_find['itime'],
            $goods_find['utime'],
            $goods_find['dtime'],
            $goods_find['datastatus']
        );
        $res        = $goods_find;
        return $this->json($res);
    }

}
