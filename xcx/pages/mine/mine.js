import { getUserCenter, getOrderNumber } from '../../utils/api'
const app = getApp()

Page({

  // 页面的初始数据
  data: {
    userInfo: {},
    wait_pay_num: 0, // 待付款
    complete_pay_num: 0, // 待发货
    send_goods_num: 0, // 待收货
    complete_goods_num: 0, // 已完成
    complete_refund_num: 0 // 已退款
  },

  // 生命周期函数--监听页面加载
  onShow: function () {
    this.getData()
  },

  goOrderList(e){
    if (app.globalData.openid) {
      wx.setStorageSync('orderType', e.currentTarget.dataset.t)
      wx.navigateTo({
        url: '/pages/order/order'
      })
    } else {
      wx.navigateTo({
        url: '/pages/login/login'
      })
    }
  },

  toAddress() {
    if (app.globalData.openid) {
      wx.navigateTo({
        url: '/pages/address/address'
      })
    } else {
      wx.navigateTo({
        url: '/pages/login/login'
      })
    }
  },

  // 初始化数据
  getData(){
    const _this = this
    const wx_openid = app.globalData.openid
    getUserCenter({ wx_openid }).then(resp => {
      _this.setData({
        userInfo: resp
      })
    })
    getOrderNumber({ wx_openid }).then(resp => {
      this.setData({
        complete_goods_num: resp.complete_goods_num,
        complete_pay_num: resp.complete_pay_num,
        complete_refund_num: resp.complete_refund_num,
        send_goods_num: resp.send_goods_num,
        wait_pay_num: resp.wait_pay_num
      })
    })
    wx.stopPullDownRefresh()
  },

  // 下拉刷新
  onPullDownRefresh: function() {
    this.getData()
  }

})