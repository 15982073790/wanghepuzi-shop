/*
 * @Author: your name
 * @Date: 2020-07-13 14:58:26
 * @LastEditTime: 2020-07-23 17:38:06
 * @LastEditors: Please set LastEditors
 * @Description: In User Settings Edit
 * @FilePath: \Hui\pages\content\content.js
 */ 
import { getOptions } from '../../utils/util'
import { getOneGoods, addGoodsCart, createOrder, getAddressList } from '../../utils/api'
import Toast from '@vant/weapp/toast/toast';
const app = getApp()
Page({

  // 页面的初始数据
  data: {
    openid: '',
    goods_id: '',
    goodInfo: {},
    detail_top_img: [],
    detail_img: [],
    addressList: [],
    minBuyNum: 1,
    number: 1
  },

  // 生命周期函数--监听页面加载
  onShow: function () {
    const _this = this
    const wx_openid = app.globalData.openid
    this.getList()
    // 判断是否有地址
    getAddressList({ wx_openid }).then(res => {
      _this.setData({
        addressList: res
      })
    })
  },

  // 初始化数据
  getList(){
    const _this = this
    const options = getOptions()
    const wx_openid = app.globalData.openid
    getOneGoods({ goods_id: options.d, wx_openid }).then(resp => {
      _this.setData({
        openid: wx_openid,
        goods_id: options.d,
        minBuyNum: parseInt(resp.min_buy_num),
        number: parseInt(resp.min_buy_num),
        detail_top_img: resp.detail_top_img.split(','),
        detail_img: resp.detail_img.split(','),
        goodInfo: resp
      })
    }).catch(err => Toast(err))
    wx.stopPullDownRefresh()
  },

  // 下拉刷新
  onPullDownRefresh: function() {
    this.getList()
  },

  addCart(){
    const { goods_id, number: goods_count, 'openid': wx_openid } = this.data
    
    // 未登录就要跳转到未登录页
    if (!wx_openid) {
      Toast('请先授权登录')
      setTimeout(function(){
        wx.navigateTo({
          url: "/pages/login/login"
        })
      }, 300)
      return false
    }
    addGoodsCart({goods_id, wx_openid, goods_count}).then(res => {
      Toast('添加成功')
    }).catch(err => Toast(err))
  },

  // 改变商品的数量
  onChangeNum(e){
    this.setData({
      number: e.detail
    })
  },

  goPurchase() {
    const { goods_id, number, addressList, 'openid': wx_openid } = this.data

    // 未登录就要跳转到未登录页
    if (!wx_openid) {
      Toast('请先授权登录')
      setTimeout(function(){
        wx.navigateTo({
          url: "/pages/login/login"
        })
      }, 300)
      return false
    }

    if (!addressList.length) {
      Toast('请先添加收货地址')
      setTimeout(function(){
        wx.navigateTo({
          url: "/pages/address/address"
        })
      }, 300)
      return false
    }
    Toast.loading({
      mask: true,
      message: '下单中...',
    });
    createOrder({wx_openid, goods_list: JSON.stringify([{ 'goods_id': goods_id, 'goods_count': number }])}).then(res => {
      if (res && res.order_id) {
        setTimeout(function(){
          Toast.clear()
          wx.removeStorageSync('selectAddress')
          wx.navigateTo({
            url: '/pages/confirm/confirm?d=' + res.order_id
          })
        }, 1000)
      } else { Toast.clear()}
    }).catch(err => {
      Toast.clear()
      Toast(err)
    })
  },

  // 切换到购物车页面
  goCart(){
    wx.switchTab({
      url: '/pages/cart/cart'
    })
  }
})