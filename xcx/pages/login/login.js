// pages/login/login.js
import { toLogin, getOpenid } from '../../utils/api'
import Toast from '@vant/weapp/toast/toast';
const app = getApp()

Page({

  data: {
  },


  onShow(){
    let that = this
    wx.login({
      success (res) {
        if (res.code) {
          // 发起网络请求
          getOpenid({code: res.code}).then(res => {
            that.setData({
              openid: res.openid
            })
          })
        } else {
          console.log('登录失败！' + res.errMsg)
        }
      }
    })
  },

  // 登录按钮
  login(e){
    const { nickName, avatarUrl } = e.detail.userInfo
    const { openid } = this.data

    Toast.loading({
      mask: true,
      message: '登录中...',
    });

    toLogin({
      wx_openid: openid,
      wx_name: nickName,
      wx_avatar: avatarUrl
    }).then(res => {
      console.log(res)
      Toast('登录成功');
      try {
        app.globalData.openid = openid // 存到全局数据里
        wx.setStorageSync('openid', openid)
        wx.setStorageSync('wx_name', nickName)
        wx.setStorageSync('wx_avatar', avatarUrl)
        setTimeout(function(){
          wx.navigateBack({
            delta: 1
          })
        }, 1000)
        // wx.switchTab({
        //   url: '/pages/mine/mine'
        // })
      } catch (e) {
        console.log(e)
      }
    }).catch(err => {
      Toast(err)
    }).finally(() => {
      Toast.clear()
    })
  }
})