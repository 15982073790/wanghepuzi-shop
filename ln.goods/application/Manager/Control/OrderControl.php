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
use Common\Logic\OrderLogic;

/**
 * @OpDescription(whatFor="订单",codeMonkey="")
 */
class OrderControl extends Control
{
    public $middleware = [
        'updateorderstatusOp'    => [
            'Common\Middleware\ManagerOrderUpdateorderstatus'
        ],
    ];

    /**
     * @@OpDescription(whatFor="订单列表",codeMonkey="")
     */
    public function indexOp(Request $request, Goods $new_goods, Order $new_order, OrderGoods $new_order_goods)
    {
        if(!empty($request['isexport'])){
            $request['curpage'] = '1';
            $request['pagesize'] = PHP_INT_MAX;
        }else{
            $request['curpage']  = $request['curpage'] ?: 1;
            $request['pagesize'] = $request['pagesize'] ?: 10;
        }
        //通过商品名获取order_id
        if ( !empty($request['goods_name']) ) {
            $order_arr = $new_order_goods->selectByOrdergoodsGoods(['goods_name' => ['like', $request['goods_name']]], [],'order_id');
            if ( empty($order_arr) ) {
                $res = AppTools::mmorePage([], 0, $request['curpage'], $request['pagesize']);
                return $this->json($res);
            } else {
                $order_where['order_id'] = [
                    'in',
                    implode(',', array_column($order_arr, 'order_id'))
                ];
            }
        }
        //支付编号筛选
        if ( !empty($request['pay_sn']) ) {
            $order_where['pay_sn'] = ['like', $request['pay_sn'] . '%'];
        }
        //订单编号筛选
        if ( !empty($request['order_sn']) ) {
            $order_where['order_sn'] = ['like', $request['order_sn'] . '%'];
        }
        //订单状态筛选
        if ( !empty($request['order_status']) ) {
            if($request['order_status']==-2) {
                $order_where['order_delete'] = 2;
            }else{
                $order_where['order_status'] = $request['order_status'];
            }
        }
        //创建时间筛选
        if ( !empty($request['itime_start']) && empty($request['itime_end']) ) {
            $order_where['itime'] = ['egt', $request['itime_start']];
        } elseif ( empty($request['itime_start']) && !empty($request['itime_end']) ) {
            $order_where['itime'] = ['elt', $request['itime_end']];
        } elseif ( !empty($request['itime_start']) && !empty($request['itime_end']) ) {
            $order_where['itime'] = ['between', "{$request['itime_start']},{$request['itime_end']}"];
        }
        //支付时间筛选
        if ( !empty($request['pay_time_start']) && empty($request['pay_time_end']) ) {
            $order_where['pay_time'] = ['egt', $request['pay_time_start']];
        } elseif ( empty($request['pay_time_start']) && !empty($request['pay_time_end']) ) {
            $order_where['pay_time'] = ['elt', $request['pay_time_end']];
        } elseif ( !empty($request['pay_time_start']) && !empty($request['pay_time_end']) ) {
            $order_where['pay_time'] = ['between', "{$request['pay_time_start']},{$request['pay_time_end']}"];
        }
        //结束
        //通过用户名/手机号获取wx_openid
        if ( !empty($request['user_name_tel']) ) {
            $user_user_selectbycondition = [
                'where' => ['user_name|tel' => ['like', $request['user_name_tel'] . '%']],
                'field' => 'wx_openid'
            ];
            $user_info_response          = $address_info_response = RpcRequest::syncRequest('user-user-selectbycondition', $user_user_selectbycondition);
            if ( $user_info_response['code'] != 1 ) {
                return $this->json('依赖user-user-selectbycondition:' . $user_info_response['message'], $user_info_response['code']);
            }
            if ( empty($user_info_response['data']) ) {
                $res = AppTools::mmorePage([], 0, $request['curpage'], $request['pagesize']);
                return $this->json($res);
            } else {
                $order_where['wx_openid'] = [
                    'in',
                    implode(',', array_column($user_info_response['data'], 'wx_openid'))
                ];
            }
        }
        //结束
        $order_arr   = $new_order->selectByCurpage($request['curpage'], $request['pagesize'], $order_where);
        $order_count = $new_order->countByCurpage($order_where);
        //获取用户信息
        $wx_openid_arr               = array_column($order_arr, 'wx_openid');
        $wx_openids                  = implode(',', $wx_openid_arr);
        $user_user_selectbycondition = [
            'where' => ['wx_openid' => ['in', $wx_openids]],
            'field' => 'wx_openid,user_name,tel'
        ];
        $user_info_response          = $address_info_response = RpcRequest::syncRequest('user-user-selectbycondition', $user_user_selectbycondition);
        if ( $user_info_response['code'] != 1 ) {
            return $this->json('依赖user-user-selectbycondition:' . $user_info_response['message'], $user_info_response['code']);
        }
        $user_arr = Arr::arrayToArrayKey($user_info_response['data'], 'wx_openid');
        //结束
        //获取商品信息
        $order_id_arr    = array_column($order_arr, 'order_id');
        $order_goods_arr = $new_order_goods->selectByOrdergoodsGoods(['order_id' => ['in', $order_id_arr]], [], 'order_id,order_goods.goods_id,goods_name,goods_count,order_goods.goods_price,goods_sn');
        foreach($order_goods_arr as $key=>&$value){
            $value['goods_price'] = sprintf("%.2f", $value['goods_price'] / 100);
        }
        $order_goods_arr = Arr::arrayToArrayKey($order_goods_arr, 'order_id', 1);
        //结束
        foreach ( $order_arr as $key => &$value ) {
            switch ( $value['order_status'] ) {
                case 1:
                    $value['order_status_name'] = '待支付';
                    break;
                case -1:
                    $value['order_status_name'] = '已取消';
                    break;
                case 2:
                    $value['order_status_name'] = '已支付';
                    break;
                case -3:
                    $value['order_status_name'] = '退款中';
                    break;
                case 3:
                    $value['order_status_name'] = '已退款';
                    break;
                case 4:
                    $value['order_status_name'] = '已发货';
                    break;
                case 5:
                    $value['order_status_name'] = '已完成';
                    break;
                default:
                    $value['order_status_name'] = '';
                    break;
            }
            if($value['expire_status']==2){
                $value['order_status_name'] .= '-已过期';
            }
            if($value['order_delete']==2){
                $value['order_status_name'] .= '-已删除';
            }
            $value['user_name']          = $user_arr[$value['wx_openid']]['user_name'];
            $value['tel']                = $user_arr[$value['wx_openid']]['tel'];
            $value['goods_names']        = implode(',', array_column($order_goods_arr[$value['order_id']], 'goods_name'));
            $value['goods_counts']       = implode(',', array_column($order_goods_arr[$value['order_id']], 'goods_count'));
            $value['goods_prices']       = implode(',', array_column($order_goods_arr[$value['order_id']], 'goods_price'));
            $value['goods_sns']          = implode(',', array_column($order_goods_arr[$value['order_id']], 'goods_sn'));
            $value['total_goods_price']  = sprintf("%.2f", $value['total_goods_price'] / 100);
            $value['total_area_price']   = sprintf("%.2f", $value['total_area_price'] / 100);
            $value['total_coupon_price'] = sprintf("%.2f", $value['total_coupon_price'] / 100);
            $value['total_pay_money']    = sprintf("%.2f", $value['total_pay_money'] / 100);
        }
        if(!empty($request['isexport'])){
            //生成excel
            $new_php_excel = new \PHPExcel();
            $newe_sheet = $new_php_excel->getActiveSheet();
            $set_title = '订单列表';
            $newe_sheet->setTitle($set_title);
            $newe_sheet->setCellValue('A1','订单编号');
            $newe_sheet->setCellValue('B1','商品名');
            $newe_sheet->setCellValue('C1','商品数');
            $newe_sheet->setCellValue('D1','商品单价');
            $newe_sheet->setCellValue('E1','商品编号');
            $newe_sheet->setCellValue('F1','支付编号');
            $newe_sheet->setCellValue('G1','用户昵称');
            $newe_sheet->setCellValue('H1','手机号');
            $newe_sheet->setCellValue('I1','物流');
            $newe_sheet->setCellValue('J1','收货地址');
            $newe_sheet->setCellValue('K1','商品总价(元)');
            $newe_sheet->setCellValue('L1','物流费用(元)');
            $newe_sheet->setCellValue('M1','优惠金额(元)');
            $newe_sheet->setCellValue('N1','实付金额(元)');
            $newe_sheet->setCellValue('O1','订单状态');
            $newe_sheet->setCellValue('P1','创建时间');
            foreach($order_arr as $key=>$val){
                $newe_sheet->setCellValue('A'.($key+2),$val['order_sn']);
                $newe_sheet->setCellValue('B'.($key+2),$val['goods_names']);
                $newe_sheet->setCellValue('C'.($key+2),$val['goods_counts']);
                $newe_sheet->setCellValue('D'.($key+2),$val['goods_prices']);
                $newe_sheet->setCellValue('E'.($key+2),$val['goods_sns']);
                $newe_sheet->setCellValue('F'.($key+2),$val['pay_sn']);
                $newe_sheet->setCellValue('G'.($key+2),$val['user_name']);
                $newe_sheet->setCellValue('H'.($key+2),$val['tel']);
                $newe_sheet->setCellValue('I'.($key+2),$val['shipper_code'].$val['waybill_number']);
                $newe_sheet->setCellValue('J'.($key+2),$val['address']);
                $newe_sheet->setCellValue('K'.($key+2),$val['total_goods_price']);
                $newe_sheet->setCellValue('L'.($key+2),$val['total_area_price']);
                $newe_sheet->setCellValue('M'.($key+2),$val['total_coupon_price']);
                $newe_sheet->setCellValue('N'.($key+2),$val['total_pay_money']);
                $newe_sheet->setCellValue('O'.($key+2),$val['order_status_name']);
                $itime = $val['itime'] ?  date('Y-m-d H:i:s',$val['itime']) : '';
                $newe_sheet->setCellValue('P'.($key+2),$itime);
            }
            $obj_Writer = \PHPExcel_IOFactory::createWriter($new_php_excel, 'Excel5');
            header('Content-Type: application/vnd.ms-excel');
            header("Content-Disposition: attachment;filename=订单列表.xls");
            header('Cache-Control: max-age=0');
            $obj_Writer -> save('php://output');
        }
        $res = AppTools::mmorePage($order_arr, $order_count, $request['curpage'], $request['pagesize']);
        return $this->json($res);
    }
    /**
     * @@OpDescription(whatFor="订单状态列表",codeMonkey="")
     */
    public function orderstatuslistOp(){
        $res = [
            '1'  => '待支付',
            '-1' => '已取消',
            '-2' => '已删除',
            '2'  => '已支付',
            '-3' => '退款中',
            '3'  => '已退款',
            '4'  => '已发货',
            '5'  => '已完成'
        ];
        return $this->json($res);
    }
    /**
     * @@OpDescription(whatFor="订单详情",codeMonkey="")
     */
    public function detailOp(Request $request, Order $new_order, OrderGoods $new_order_goods)
    {
        $order_id                         = $request['order_id'];
        $order_find                       = $new_order->findByCondition(['order_id' => $order_id]);
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
        if($order_find['order_delete']==2){
            $order_find['order_status_name'] .= '(已删除)';
        }
        //获取用户信息
        $wx_openid                 = $order_find['wx_openid'];
        $user_user_findbycondition = [
            'wx_openid' => $wx_openid,
            'field'     => 'wx_openid,user_name,tel'
        ];
        $user_info_response        = $address_info_response = RpcRequest::syncRequest('user-user-findbycondition', $user_user_findbycondition);
        if ( $user_info_response['code'] != 1 ) {
            return $this->json('依赖user-user-findbycondition:' . $user_info_response['message'], $user_info_response['code']);
        }
        $order_find['user_name'] = $user_info_response['data']['user_name'];
        $order_find['tel']       = $user_info_response['data']['tel'];
        //获取商品信息
        $order_goods_arr          = $new_order_goods->selectByOrdergoodsGoods(['order_id' => $order_id], [], 'order_id,order_goods.goods_id,goods_name,goods_sn,order_goods.goods_price,goods_count,goods_total_price,area_total_price,coupon_total_price');
        foreach($order_goods_arr as $key=>&$value){
            $value['goods_price']        = sprintf("%.2f", $value['goods_price'] / 100);
            $value['goods_total_price']  = sprintf("%.2f", $value['goods_total_price'] / 100);
            $value['area_total_price']   = sprintf("%.2f", $value['area_total_price'] / 100);
            $value['coupon_total_price'] = sprintf("%.2f", $value['coupon_total_price'] / 100);
        }
        $order_find['goods_info'] = $order_goods_arr;
        //结束
        unset(
            $order_find['utime'],
            $order_find['dtime'],
            $order_find['datastatus']
        );
        $res = $order_find;
        return $this->json($res);
    }
    /**
     * @@OpDescription(whatFor="物流公司编号",codeMonkey="")
     */
    public function shippercodeOp()
    {
        $res = Config::get('shipper_code');
        return $this->json($res);
    }
    /**
     * @@OpDescription(whatFor="更新运单号",codeMonkey="")
     */
    public function updatewaybillnumberOp(Request $request, Order $new_order)
    {
        $order_id       = $request['order_id'];
        $shipper_code   = $request['shipper_code'];
        $waybill_number = $request['waybill_number'];
        $order_find = $new_order->findByCondition(['order_id'=>$order_id]);
        if(!empty($order_find['send_time'])) {
            $update_count = $new_order->updateData(['order_id' => $order_id], ['shipper_code' => $shipper_code, 'waybill_number' => $waybill_number]);
        }else{
            $update_count = $new_order->updateData(['order_id' => $order_id], ['shipper_code' => $shipper_code, 'waybill_number' => $waybill_number,'send_time'=>time()]);
        }
        if (empty($update_count)) {
            return $this->json('更新失败', -1);
        }
        return $this->json('更新成功');
    }

    /**
     * @@OpDescription(whatFor="更新订单状态",codeMonkey="")
     */
    public function updateorderstatusOp(Request $request, Order $new_order)
    {
        $order_id     = $request['order_id'];
        $order_status = $request['order_status'];
        $update_count = $new_order->updateData(['order_id' => $order_id], ['order_status' => $order_status]);
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




}
