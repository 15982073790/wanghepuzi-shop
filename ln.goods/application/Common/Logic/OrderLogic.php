<?php

namespace Common\Logic;

use Common\Model\Order;
use MrStock\System\Helper\Config;
use MrStock\System\Helper\HttpRequest;
class OrderLogic
{
    public function querywaybill($order_id){
        $new_order = new Order();
        $order_find = $new_order->findByCondition(['order_id'=>$order_id],'shipper_code,waybill_number');
        //电商ID
        defined('EBusinessID') or define('EBusinessID',Config::get('ebusiness_id'));
        //电商加密私钥，快递鸟提供，注意保管，不要泄漏
        defined('AppKey') or define('AppKey', Config::get('app_key'));
        //请求url
        defined('ReqURL') or define('ReqURL', 'http://api.kdniao.com/Ebusiness/EbusinessOrderHandle.aspx');
        $requestData= "{'OrderCode':'','ShipperCode':'{$order_find['shipper_code']}','LogisticCode':'{$order_find['waybill_number']}'}";

        $datas = array(
            'EBusinessID' => EBusinessID,
            'RequestType' => '1002',
            'RequestData' => urlencode($requestData) ,
            'DataType' => '2',
        );
        $datas['DataSign'] = urlencode(base64_encode(md5($requestData.AppKey)));

        $result=HttpRequest::query(ReqURL, $datas,1);

        return $result;
    }
    //快递100接口
    public function querywaybill100($order_id){
        $new_order = new Order();
        $order_find = $new_order->findByCondition(['order_id'=>$order_id],'shipper_code,waybill_number');
        $key = 'xerpmSzi633';						//客户授权key
        $customer = '69AA360B74B9686A2EC6E800A9BF753C';					//查询公司编号
        $param = array (
            'com' => $order_find['shipper_code'],			//快递公司编码
            'num' => $order_find['waybill_number'],	//快递单号
            'phone' => '',				//手机号
            'from' => '',				//出发地城市
            'to' => '',					//目的地城市
            'resultv2' => '1'			//开启行政区域解析
        );

        //请求参数
        $post_data = array();
        $post_data["customer"] = $customer;
        $post_data["param"] = json_encode($param);
        $sign = md5($post_data["param"].$key.$post_data["customer"]);
        $post_data["sign"] = strtoupper($sign);

        $url = 'http://poll.kuaidi100.com/poll/query.do';	//实时查询请求地址

        $params = "";
        foreach ($post_data as $k=>$v) {
            $params .= "$k=".urlencode($v)."&";		//默认UTF-8编码格式
        }
        $post_data = substr($params, 0, -1);
        $result=HttpRequest::query($url, $post_data,0);
        return $result;
    }
}