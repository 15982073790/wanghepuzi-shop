// pages/home/home.js
import Toast from '@vant/weapp/toast/toast';
import { openPay, getOpenid, hasBind } from '../../utils/api'
const app = getApp()

Page({

  /**
   * 页面的初始数据
   */
  data: {
    openid: ''
  },

  onShow(){
    // let _this = this
    wx.getStorage({
      key: 'openid',
      success (res) {
        // 授权过了的
        const openid = res.data
        _this.saveOpenid(openid)
        _this.checkHasBind(openid) // 检查是否绑定过
      },
      fail(){
        // 说明还没授权过
        wx.login({
          success (res) {
            if (res.code) {
              //发起网络请求
              getOpenid({code: res.code}).then(resp => {
                wx.setStorageSync('openid', resp.openid)
                _this.saveOpenid(resp.openid) // 讲openid保存到全局globalData里 后面的页面要用直接重里面取
                _this.checkHasBind(resp.openid) // 检查是否绑定过
              }).catch(err => Toast(err))
            } else {
              console.log('登录失败！' + res.errMsg)
            }
          }
        })
      }
    })
  },

  saveOpenid(openid){
    wx.setStorageSync('openid', openid)
    app.globalData.openid = openid // 存到全局数据里
    this.setData({
      openid
    })
  },

  checkHasBind(wx_openid){
    hasBind({ wx_openid }).then(res => {
      // 绑定过了 直接跳转首页
      if (res && res.is_bind === 1) {
        wx.switchTab({
          url: '/pages/index/index'
        })
      }
    }).catch(err => Toast(err))
  }
  
})