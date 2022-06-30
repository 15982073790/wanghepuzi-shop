/*
 * @Author: your name
 * @Date: 2020-05-11 10:04:27
 * @LastEditTime: 2020-07-23 17:22:21
 * @LastEditors: Please set LastEditors
 * @Description: In User Settings Edit
 * @FilePath: \cloud.manager\src\api\list.js
 */
import request from '@/utils/request'

// 订单列表
export function getOrderList (data) {
  return request({
    url: '/?c=order&a=index&v=manager&site=goods',
    method: 'post',
    data
  })
}

// 更改订单状态
export function updateOrderStatus (data) {
  return request({
    url: '/?c=order&a=updateorderstatus&v=manager&site=goods',
    method: 'post',
    data
  })
}

// 用户列表
export function getUserList (data) {
  return request({
    url: '/?c=user&a=index&v=manager&site=user',
    method: 'post',
    data
  })
}

// 停启用户
export function changeUserStatus (data) {
  return request({
    url: '/?c=user&a=startopende&v=manager&site=user',
    method: 'post',
    data
  })
}

// 编辑或者更新用户
export function updateUserInfo (data) {
  const affix = data.user_id ? 'update' : 'add'
  return request({
    url: `/?c=user&a=${affix}info&v=manager&site=user`,
    method: 'post',
    data
  })
}

// 商品列表
export function getGoodsList (data) {
  return request({
    url: '/?c=goods&a=index&v=manager&site=goods',
    method: 'post',
    data
  })
}

// 添加商品
export function addGoods (data) {
  return request({
    url: '/?c=goods&a=addinfo&v=manager&site=goods',
    method: 'post',
    data
  })
}

// 区域列表
export function getAreaList (data) {
  return request({
    url: '/?c=user&a=arealist&v=manager&site=user',
    method: 'post',
    data
  })
}

// 财务列表
export function getFinanceList (data) {
  return request({
    url: '/?c=statistics&a=index&v=manager&site=goods',
    method: 'post',
    data
  })
}

// 发货
export function faLogis (data) {
  return request({
    url: '/?c=order&a=updatewaybillnumber&v=manager&site=goods',
    method: 'post',
    data
  })
}

// 文件上传
export function uploadFileImg () {
  return request({
    url: '/?c=uploadfile&a=getosstoken&v=manager&site=uploadfile',
    method: 'post'
  })
}

// 新增商品
export function addNewGoods (data) {
  return request({
    url: '/?c=goods&a=addinfo&v=manager&site=goods',
    method: 'post',
    data
  })
}

// 修改商品
export function updateGoods (data) {
  return request({
    url: '/?c=goods&a=updateinfo&v=manager&site=goods',
    method: 'post',
    data
  })
}

// 更新商品状态
export function updateGoodStatus (data) {
  return request({
    url: '/?c=goods&a=updateindex&v=manager&site=goods',
    method: 'post',
    data
  })
}

// 退款
export function tuiMoney (data) {
  return request({
    url: '/?c=payment&a=refund&v=manager&site=goods',
    method: 'post',
    data
  })
}
