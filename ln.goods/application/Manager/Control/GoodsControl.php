<?php

namespace Manager\Control;

use MrStock\System\MJC\Control;
use MrStock\System\MJC\Http\Request;
use MrStock\System\Helper\Arr;
use Common\Model\Goods;
use MrStock\System\Helper\Config;
use Common\Libraries\AppTools;

/**
 * @ControlDescription(menuName="商品列表",cGroupName="商品")
 */
class GoodsControl extends Control
{
    public $middleware = [
        'addinfoOp'    => [
            'Common\Middleware\Goodsaddupdateinfo'
        ],
        'updateinfoOp' => [
            'Common\Middleware\Goodsaddupdateinfo'
        ],
    ];

    /**
     * @OpDescription(whatFor="商品列表",menuName="商品列表",codeMonkey="")
     */
    public function indexOp(Request $request, Goods $new_goods)
    {
        $request['curpage']  = $request['curpage'] ?: 1;
        $request['pagesize'] = $request['pagesize'] ?: 10;
        $list                = $new_goods->selectByCurpage($request);
        foreach ($list as $key => &$value) {
            $value['goods_price']          = sprintf("%.2f", $value['goods_price'] / 100);
            $value['goods_original_price'] = sprintf("%.2f", $value['goods_original_price'] / 100);
            $value['area_price']           = json_decode($value['area_price'], true);
            foreach ($value['area_price'] as $k => &$val) {
                $val['area_price'] = sprintf("%.2f", $val['area_price'] / 100);
            }
            $value['area_price'] = json_encode($value['area_price']);
            $value['residue_repertory_num'] = $value['repertory_num']-$value['buy_num'];
        }
        $count               = $new_goods->countByCurpage($request);
        $res                 = AppTools::mmorePage($list, $count, $request['curpage'], $request['pagesize']);
        return $this->json($res);
    }

    /**
     * @OpDescription(whatFor="列表记录更新",menuName="",codeMonkey="")
     */
    public function updateindexOp(Request $request, Goods $new_goods)
    {
        $goods_id = $request['goods_id'];
        if (!empty($request['banner_status'])) {
            if ($request['banner_status'] == 2) { //开启的时候做banner的判断
                $goods_find = $new_goods->findByCondition(['goods_id' => $goods_id]);
                if ($goods_find['quality_goods_status'] == 2) {
                    return $this->json('该商品已开启精品', -1);
                }
                if (empty($goods_find['banner_img'])) {
                    return $this->json('请上传banner图', -1);
                }
                $banner_status_open_count = $new_goods->countByCondition(['banner_status' => 2]);
                if ($banner_status_open_count > 5) {
                    return $this->json('banner商品最多开启5个', -1);
                }
            }elseif($request['banner_status'] == 1){
                $banner_status_open_count = $new_goods->countByCondition(['banner_status' => 2]);
                if ($banner_status_open_count == 1) {
                    return $this->json('banner商品最少保留一个', -1);
                }
            }
            $update_data['banner_status'] = $request['banner_status'];
        } elseif (!empty($request['quality_goods_status'])) {
            if($request['quality_goods_status']==2){
                $goods_find = $new_goods->findByCondition(['goods_id' => $goods_id]);
                if ($goods_find['banner_status'] == 2) {
                    return $this->json('该商品已开启banner', -1);
                }
                $banner_status_open_count = $new_goods->countByCondition(['quality_goods_status' => 2]);
                if ($banner_status_open_count > 3) {
                    return $this->json('精品商品最多开启3个', -1);
                }
            }elseif($request['quality_goods_status']==1){
                $banner_status_open_count = $new_goods->countByCondition(['quality_goods_status' => 2]);
                if ($banner_status_open_count == 1) {
                    return $this->json('精品商品最少开启1个', -1);
                }
            }
            $update_data['quality_goods_status'] = $request['quality_goods_status'];
        } elseif (!empty($request['coupon_status'])) {
            $update_data['coupon_status'] = $request['coupon_status'];
        } elseif (!empty($request['sort'])) {
            $update_data['sort'] = $request['sort'];
        } elseif (!empty($request['publish_status'])) {
            $update_data['publish_status'] = $request['publish_status'];
        }
        $update_count = $new_goods->updateData(['goods_id' => $goods_id], $update_data);
        if (empty($update_count)) {
            return $this->json('更新失败', -1);
        }
        return $this->json('更新成功');
    }

    /**
     * @OpDescription(whatFor="新增",menuName="新增",codeMonkey="")
     */
    public function addinfoOp(Request $request, Goods $new_goods)
    {
        //商品信息
        $goods_name           = $request['goods_name'];
        $goods_sn             = $request['goods_sn'];
        $goods_price          = $request['goods_price'] * 100;
        $goods_original_price = $request['goods_original_price'] * 100;
        $model                = $request['model'];
        $specification        = $request['specification'];
        $goods_describe       = $request['goods_describe'];
        $preview_tel          = $request['preview_tel'];
        //将物流价格转化为分
        $area_price = $request['area_price'];
        $area_price = json_decode($area_price, true);
        foreach ($area_price as $key => &$value) {
            if(!preg_match('/^[0-9]+(.[0-9]{2}){1}$/',$value['area_price'])){
                $err_message = $value['province_name'].'价格格式不对';
                return $this->json($err_message, -1);
            }
            $value['area_price'] *= 100;
        }
        $area_price = json_encode($area_price);
        //结束
        $banner_img           = $request['banner_img'];
        $cover_img            = $request['cover_img'];
        $detail_top_img       = $request['detail_top_img'];
        $detail_img           = $request['detail_img'];
        $fake_buy_num         = $request['fake_buy_num'];
        $repertory_num        = $request['repertory_num'];
        $sort                 = $request['sort'];
        $min_buy_num          = $request['min_buy_num'];
        //插入商品数据
        $data['goods_name']           = $goods_name;
        $data['goods_sn']             = $goods_sn;
        $data['goods_price']          = $goods_price;
        $data['goods_original_price'] = $goods_original_price;
        $data['model']                = $model;
        $data['specification']        = $specification;
        $data['goods_describe']       = $goods_describe;
        $data['preview_tel']          = $preview_tel;
        $data['area_price']           = $area_price;
        $data['banner_img']           = $banner_img;
        $data['cover_img']            = $cover_img;
        $data['detail_top_img']       = $detail_top_img;
        $data['detail_img']           = $detail_img;
        $data['fake_buy_num']         = $fake_buy_num;
        $data['repertory_num']        = $repertory_num;
        $data['sort']                 = $sort;
        $data['min_buy_num']                 = $min_buy_num;

        $goods_find = $new_goods->findByCondition(['goods_sn' => $goods_sn]);
        if (!empty($goods_find)) {
            return $this->json('商品编号已存在!', -1);
        }
        $new_goods->insertData($data);

        return $this->json('新增成功');
    }

    /**
     * @OpDescription(whatFor="编辑",menuName="编辑",codeMonkey="")
     */
    public function editinfoOp(Request $request, Goods $new_goods)
    {
        $goods_id                           = $request['goods_id'];
        $goods_find                         = $new_goods->findByCondition(['goods_id' => $goods_id]);
        $goods_find['goods_price']          = sprintf("%.2f", $goods_find['goods_price'] / 100);
        $goods_find['goods_original_price'] = sprintf("%.2f", $goods_find['goods_original_price'] / 100);
        $area_price_arr                     = json_decode($goods_find['area_price'], true);
        foreach ($area_price_arr as $key => &$value) {
            $value['area_price'] = sprintf("%.2f", $value['area_price'] / 100);
        }
        $goods_find['area_price'] = json_encode($goods_find['area_price']);
        $res                      = $goods_find;
        return $this->json($res);
    }

    /**
     * @OpDescription(whatFor="更新",menuName="",codeMonkey="")
     */
    public function updateinfoOp(Request $request, Goods $new_goods)
    {
        $goods_id = $request['goods_id'];
        //商品信息
        $goods_name           = $request['goods_name'];
        $goods_sn             = $request['goods_sn'];
        $goods_price          = $request['goods_price']*100;
        $goods_original_price = $request['goods_original_price']*100;
        $model                = $request['model'];
        $specification        = $request['specification'];
        $goods_describe       = $request['goods_describe'];
        $preview_tel = $request['preview_tel'];
        //将物流价格转化为分
        $area_price = $request['area_price'];
        $area_price = json_decode($area_price, true);
        foreach ($area_price as $key => &$value) {
            if(!preg_match('/^[0-9]+(.[0-9]{2}){1}$/',$value['area_price'])){
                $err_message = $value['province_name'].'地区价格格式不对';
                return $this->json($err_message, -1);
            }
            $value['area_price'] *= 100;
        }
        $area_price = json_encode($area_price);
        //结束
        $banner_img           = $request['banner_img'];
        $cover_img            = $request['cover_img'];
        $detail_top_img       = $request['detail_top_img'];
        $detail_img           = $request['detail_img'];
        $fake_buy_num         = $request['fake_buy_num'];
        $repertory_num        = $request['repertory_num'];
        $sort                 = $request['sort'];
        $min_buy_num          = $request['min_buy_num'];
        //插入商品数据
        $data['goods_name']           = $goods_name;
        $data['goods_sn']             = $goods_sn;
        $data['goods_price']          = $goods_price;
        $data['goods_original_price'] = $goods_original_price;
        $data['model']                = $model;
        $data['specification']        = $specification;
        $data['goods_describe']       = $goods_describe;
        $data['preview_tel']          = $preview_tel;
        $data['area_price']           = $area_price;
        $data['banner_img']           = $banner_img;
        $data['cover_img']            = $cover_img;
        $data['detail_top_img']       = $detail_top_img;
        $data['detail_img']           = $detail_img;
        $data['fake_buy_num']         = $fake_buy_num;
        $data['repertory_num']        = $repertory_num;
        $data['sort']                 = $sort;
        $data['min_buy_num']          = $min_buy_num;
        $goods_find                   = $new_goods->findByCondition(['goods_id' => ['neq', $goods_id], 'goods_sn' => $goods_sn]);
        if (!empty($goods_find)) {
            return $this->json('商品编号已存在!', -1);
        }
        $update_count = $new_goods->updateData(['goods_id' => $goods_id], $data);
        if (empty($update_count)) {
            return $this->json('更新失败', -1);
        }
        return $this->json('更新成功');
    }

}
