<?php

namespace Manager\Control;

use MrStock\System\Helper\RpcRequest;
use MrStock\System\MJC\Control;
use MrStock\System\MJC\Http\Request;
use MrStock\System\Helper\HttpRequest;
use MrStock\System\Helper\Arr;
use Common\Model\Goods;
use Common\Model\Order;
use Common\Model\OrderGoods;
use Common\Logic\PayNotifyCallBackLogic;
use MrStock\System\Helper\Config;
use Common\Libraries\AppTools;
use MrStock\System\MJC\Facade\Log;

/**
 * @OpDescription(whatFor="支付",codeMonkey="")
 */
class PaymentControl extends Control
{
    public $middleware = [

    ];

    /**
     * @@OpDescription(whatFor="退款",codeMonkey="")
     */
    public function refundOp(Request $request, Order $new_order,OrderGoods $new_order_goods,Goods $new_goods)
    {
        $order_id   = $request['order_id'];
        $order_find = $new_order->findByCondition(['order_id' => $order_id], 'pay_sn,total_pay_money');
        $refund_sn  = date('YmdHis').substr(explode(' ',microtime())[0],2,6);
        $new_order->beginTransaction();
        $update_count = $new_order->updateData(['order_id' => $order_id], ['order_status' => 3, 'refund_sn' => $refund_sn, 'refund_time' => time()]);
        if (empty($update_count)) {
            throw new \Exception('更新订单状态失败', -1);
        }
        //购买后减去商品数
        $order_goods_arr = $new_order_goods->selectByCondition(['order_id' => $order_id]);
        foreach ($order_goods_arr as $key => $value) {
            $new_goods->setDecByCondition(['goods_id' => $value['goods_id']], 'buy_num', $value['goods_count']);
        }
        //结束
        if($order_find['total_pay_money']==0){
            $new_order->commit();
            return $this->json('退款成功');
        }
        $transaction_id = $order_find["pay_sn"];
        $total_fee      = $order_find['total_pay_money'];
        $refund_fee     = $order_find['total_pay_money'];
        require_once "../vendor/wxpay/lib/WxPay.Api.php";
        require_once "../vendor/wxpay/lib/WxPay.Config.php";
        $input = new \WxPayRefund();
        $input->SetTransaction_id($transaction_id);
        $input->SetTotal_fee($total_fee);
        $input->SetRefund_fee($refund_fee);

        \WxPayConfig::$xcx_appid  = Config::get('xcx_appid');
        \WxPayConfig::$xcx_mchid  = Config::get('xcx_mchid');
        \WxPayConfig::$xcx_key    = Config::get('xcx_key');
        \WxPayConfig::$xcx_secret = Config::get('xcx_secret');
        $config                   = new \WxPayConfig();
        $input->SetOut_refund_no($refund_sn);
        $input->SetOp_user_id($config->GetMerchantId());
        $refund_response = \WxPayApi::refund($config, $input);
        if ($refund_response['result_code'] != 'SUCCESS') {
            throw new \Exception('退款失败,请稍后再试', -1);
        }
        $new_order->commit();
        return $this->json('退款成功');

    }

}


