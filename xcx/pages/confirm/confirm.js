import { getOptions } from '../../utils/util'
import { getOneOrder, updateOrderAddress, openPay } from '../../utils/api'
import Toast from '@vant/weapp/toast/toast';
const app = getApp()
Page({

  /**
   * 页面的初始数据
   */
  data: {
    orderInfo: {},
    address: '',
    userName: '',
    userTel: '',
    order_goods: []
  },

  onShow(){
    const _this = this
    const options = getOptions()
    const wx_openid = app.globalData.openid
    try {
      var selectAddress = wx.getStorageSync('selectAddress')
      if (selectAddress) {
        const { address_id } = JSON.parse(selectAddress)
        updateOrderAddress({wx_openid, order_id: options.d, address_id }).then(res => {
          console.log('更新成功')
        }).catch(err => {
          Toast(err)
        }).finally(() => {
          _this.getList(options.d)
        })
      } else {
        _this.getList(options.d)
      }
    } catch (e) {
      _this.getList(options.d)
    }
  },
  
  getList(order_id){
    const _this = this
    const wx_openid = app.globalData.openid
    getOneOrder({ order_id, wx_openid }).then(res => {
      const addressArr = res.address.split(' ')
      _this.setData({
        orderInfo: res,
        address: addressArr.slice(0, 4).join(''),
        userName: addressArr[4],
        userTel: addressArr[5]
      })
    }).catch(err => Toast(err))
  },

  pay(){
    const wx_openid = app.globalData.openid
    const options = getOptions()
    openPay({
      order_id: options.d,
      wx_openid
    }).then(response => {
      if (response && response.pay_success === 1) {
        // 不需要支付
        Toast('支付成功')
        setTimeout(function(){
          wx.redirectTo({
            url: '/pages/detail/detail?d=' + options.d
          })
        }, 300)
      } else {
        wx.requestPayment({
          timeStamp: response.timeStamp,
          nonceStr: response.nonceStr,
          package: response.package,
          signType: response.signType,
          paySign: response.paySign,
          success (res) { 
            Toast('支付成功')
            setTimeout(function(){
              wx.redirectTo({
                url: '/pages/detail/detail?d=' + options.d
              })
            }, 300)
          },
          fail (res) {
            Toast('支付失败')
          }
        })
      }
    }).catch(err => Toast(err))
  },

  copyText: function (e) {
    wx.setClipboardData({
      data: e.currentTarget.dataset.text,
      success: function (res) {
        wx.getClipboardData({
          success: function (res) {
            wx.showToast({
              title: '复制成功',
              icon: 'none'
            })
          }
        })
      }
    })
  }
  
})