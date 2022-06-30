import request from "./request.js"

// 获取openid
export function getOpenid(params) {
  return request('/?c=user&a=useropenid&v=app&site=user', params, {
    prompt: false
  }, 1);
}

// 是否绑定过
export function hasBind(params) {
  return request('/?c=user&a=isband&v=app&site=user', params, {
    prompt: false
  }, 1);
}

// 登录
export function toLogin(params) {
  return request('/?c=user&a=adduserinfo&v=app&site=user', params, {
    prompt: false
  }, 1);
}

// 微信支付
export function openPay(params) {
  return request('/?c=payment&a=wxpay&v=app&site=goods', params, {
    prompt: false
  }, 1);
}

// 首页商品列表
export function getIndexGoods(params) {
  return request('/?c=goods&a=index&v=app&site=goods', params, {
    prompt: false
  }, 1);
}

// 单个商品详情
export function getOneGoods(params) {
  return request('/?c=goods&a=detail&v=app&site=goods', params, {
    prompt: false
  }, 1);
}

// 加入购物车
export function addGoodsCart(params) {
  return request('/?c=shopcart&a=incdecshopcart&v=app&site=goods', params, {
    prompt: false
  }, 1);
}

// 购物车列表
export function getCartList(params) {
  return request('/?c=shopcart&a=shopcartlist&v=app&site=goods', params, {
    prompt: false
  }, 1);
}

// 删除单个购物车商品
export function deleteOneFromCart(params) {
  return request('/?c=shopcart&a=deleteshopcart&v=app&site=goods', params, {
    prompt: false
  }, 1);
}

// 收货地址列表
export function getAddressList(params) {
  return request('/?c=address&a=addresslist&v=app&site=user', params, {
    prompt: false
  }, 1);
}

// 新增或编辑地址
export function updateAddress(params) {
  const affix = params.address_id ? 'update' : 'add'
  return request(`/?c=address&a=${affix}address&v=app&site=user`, params, {
    prompt: false
  }, 1);
}

// 获取单个地址详情
export function getOneAddress(params) {
  return request('/?c=address&a=editaddress&v=app&site=user', params, {
    prompt: false
  }, 1);
}

// 删除单个地址
export function deleteAddress(params) {
  return request('/?c=address&a=deleteaddress&v=app&site=user', params, {
    prompt: false
  }, 1);
}

// 获取地址列表
export function getArea(params) {
  return request('/?c=area&a=arealist&v=app&site=user', params, {
    prompt: false
  }, 1);
}

// 订单列表
export function getOrderList(params) {
  return request('/?c=order&a=orderlist&v=app&site=goods', params, {
    prompt: false
  }, 1);
}

// 单个订单详情
export function getOneOrder(params) {
  return request('/?c=order&a=detail&v=app&site=goods', params, {
    prompt: false
  }, 1);
}

// 修改订单状态
export function changeOrderStatus(params) {
  return request('/?c=order&a=updateorderstatus&v=app&site=goods', params, {
    prompt: false
  }, 1);
}

// 立即购买
export function createOrder(params) {
  return request('/?c=order&a=createorder&v=app&site=goods', params, {
    prompt: false
  }, 1);
}

// 更新订单地址
export function updateOrderAddress(params) {
  return request('/?c=order&a=updateaddress&v=app&site=goods', params, {
    prompt: false
  }, 1);
}

// 个人中心
export function getUserCenter(params) {
  return request('/?c=user&a=usercenter&v=app&site=user', params, {
    prompt: false
  }, 1);
}

// 物流查询接口
export function getLogistic(params) {
  return request('/?c=order&a=querywaybill&v=app&site=goods', params, {
    prompt: false
  });
}

// 订单数量
export function getOrderNumber(params) {
  return request('/?c=order&a=orderstatusnum&v=app&site=goods', params, {
    prompt: false
  }, 1);
}
