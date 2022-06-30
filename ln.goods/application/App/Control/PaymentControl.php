<?php

namespace App\Control;

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
     * @@OpDescription(whatFor="微信小程序下单",codeMonkey="")
     */
    public function wxpayOp(Request $request, Goods $new_goods, Order $new_order, OrderGoods $new_order_goods)
    {
        $order_id   = $request['order_id'];
        $wx_openid  = $request['wx_openid'];
        $order_find = $new_order->findByCondition(['order_id' => $order_id, 'wx_openid' => $wx_openid]);
        if (empty($order_find)) {
            return $this->json('没有该订单', -1);
        }
        if (empty($order_find['address'])) {
            return $this->json('地址为空,请重新选择地址', -1);
        }
        if ($order_find['expire_status']==2) {
            return $this->json('该订单已过期', -1);
        }
        if (!empty($order_find['pay_sn'])) {
            return $this->json('该订单已支付', -1);
        }
        //结束
        $total_pay_money = $order_find['total_pay_money'];//举例支付0.01
        if($total_pay_money==0){
            $update_count = $new_order->updateData(['order_id' => $order_id, 'wx_openid' => $wx_openid],['order_status'=>2]);
            if(!empty($update_count)){
                return $this->json(['pay_success'=>1]);
            }else{
                return $this->json('更新失败',-1);
            }
        }
        $body            = '惠汇精选';
        $out_trade_no = $order_find['order_sn'];
        require_once "../vendor/wxpay/lib/WxPay.Api.php";
        require_once "../vendor/wxpay/lib/WxPay.JsApiPay.php";
        require_once "../vendor/wxpay/lib/WxPay.Config.php";
        //统一下单参数
        $input = new \WxPayUnifiedOrder();
        $input->SetBody($body);
        $input->SetOut_trade_no($out_trade_no);
        $input->SetTotal_fee($total_pay_money);
        $input->SetTime_start(date("YmdHis"));
//            $input->SetTime_expire(date("YmdHis", time() + 600));
        $input->SetNotify_url(Config::get('notify_url'));
        $input->SetTrade_type("JSAPI");
        $input->SetOpenid($wx_openid);
        //结束
        //配置商户号参数
        $new_config               = new \WxPayConfig();
        \WxPayConfig::$xcx_appid  = Config::get('xcx_appid');
        \WxPayConfig::$xcx_mchid  = Config::get('xcx_mchid');
        \WxPayConfig::$xcx_key    = Config::get('xcx_key');
        \WxPayConfig::$xcx_secret = Config::get('xcx_secret');
        //结束
        $order = \WxPayApi::unifiedOrder($new_config, $input);
        //解析
        $tools           = new \JsApiPay();
        $jsApiParameters = $tools->GetJsApiParameters($order);
        $res             = json_decode($jsApiParameters, true);
        //结束
        return $this->json($res);
    }

    /**
     * @@OpDescription(whatFor="微信小程序回调",codeMonkey="")
     */
    public function notifyOp(Request $request)
    {
        require_once "../vendor/wxpay/lib/WxPay.Api.php";
        require_once '../vendor/wxpay/lib/WxPay.Notify.php';
        require_once "../vendor/wxpay/lib/WxPay.Config.php";
        \WxPayConfig::$xcx_appid  = Config::get('xcx_appid');
        \WxPayConfig::$xcx_mchid  = Config::get('xcx_mchid');
        \WxPayConfig::$xcx_key    = Config::get('xcx_key');
        \WxPayConfig::$xcx_secret = Config::get('xcx_secret');
        $new_config               = new \WxPayConfig();
        $new_notify               = new PayNotifyCallBackLogic();
        $new_notify->Handle($new_config, false);
        exit;
    }

}


