<?php

namespace Common\Middleware;


use MrStock\System\Helper\Output;
use MrStock\System\MJC\Validate;

class Goodsaddupdateinfo
{
    public function handle($request, \Closure $next)
    {
        $validate = new Validate();
        $validate->validateparam = [
            [
                'input' => $request['goods_name'],
                'require' => true,
                'message' => '商品名必填'
            ],
            [
                'input' => $request['goods_sn'],
                'require' => true,
                'message' => '商品编号必填'
            ],
            [
                'input' => $request['goods_price'],
                'require' => true,
                'validator' => 'custom',
                'regexp' => '/^[0-9]+(.[0-9]{2}){1}$/',
                'message' => '价格格式不对'
            ],
            [
                'input' => $request['model'],
                'require' => true,
                'message' => '型号必填'
            ],
            [
                'input' => $request['specification'],
                'require' => true,
                'message' => '规格必须'
            ],
            [
                'input' => $request['goods_describe'],
                'require' => true,
                'message' => '描述必须'
            ],
            [
                'input' => $request['area_price'],
                'require' => true,
                'message' => '物流价格必须'
            ],
            [
                'input' => $request['cover_img'],
                'require' => true,
                'message' => '封面图片必须'
            ],
            [
                'input' => $request['detail_img'],
                'require' => true,
                'message' => '详情图片必须'
            ],
            [
                'input' => $request['fake_buy_num'],
                'validator' => 'number',
                'message' => '购买人数必须为数字'
            ],
            [
                'input' => $request['repertory_num'],
                'require' => true,
                'validator' => 'number',
                'message' => '库存量必须为数字'
            ],
            [
                'input' => $request['min_buy_num'],
                'require' => true,
                'validator' => 'number',
                'message' => '最少购买量必须为数字'
            ]
        ];


        $error = $validate->validate();
        
        if ($error != '') {
            return Output::response($error, - 1);
        }
        return $next($request);
    }
}