import { getOptions } from '../../utils/util'
import { getOneOrder, openPay, changeOrderStatus } from '../../utils/api'
import Toast from '@vant/weapp/toast/toast';
import Dialog from '@vant/weapp/dialog/dialog';
const app = getApp()

Page({

  // 页面的初始数据
  data: {
    orderInfo: {},
    address: '',
    userName: '',
    userTel: '',
    payLoading: false
  },

  onShow(){
    this.getList()
  },

  getList(){
    const _this = this
    const { d: order_id } = getOptions()
    const wx_openid = app.globalData.openid
    getOneOrder({order_id, wx_openid}).then(res => {
      const addressArr = res.address.split(' ')
      _this.setData({
        orderInfo: res,
        address: addressArr.slice(0, 4).join(''),
        userName: addressArr[4],
        userTel: addressArr[5]
      })
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
  },

  // 取消订单
  cancelOrder(){
    const { d: order_id } = getOptions()
    const { getList } = this
    Dialog.confirm({
      message: '确定取消订单？',
      confirmButtonText: '确定',
      cancelButtonText: '取消'
    }).then(() => {
      // on confirm
      changeOrderStatus({ order_id, order_status: -1 }).then(res => {
        getList()
        Toast('取消成功')
      }).catch(err => Toast(err))
    })
    .catch(() => {
      // on cancel
    });
  },

  // 申请退款
  postTui(){
    const { d: order_id } = getOptions()
    const { getList } = this
    Dialog.confirm({
      message: '确定申请退款？',
      confirmButtonText: '确定',
      cancelButtonText: '取消'
    }).then(() => {
      // on confirm
      changeOrderStatus({ order_id, order_status: -3 }).then(res => {
        getList()
        Toast('申请成功')
      }).catch(err => Toast(err))
    })
    .catch(() => {
      // on cancel
    });
  },

  // 立即支付
  pay(){
    // 添加loading效果
    if (this.data.payLoading) {
      return false
    }
    this.setData({
      payLoading: true
    })
    const _this = this
    const { getList } = this
    const { d: order_id } = getOptions()
    const wx_openid = app.globalData.openid
    openPay({
      order_id,
      wx_openid
    }).then(response => {
      if (response && response.pay_success === 1) {
        // 不需要支付
        Toast('支付成功')
        getList()
        setTimeout(function(){
          _this.setData({
            payLoading: false
          })
        }, 1500)
      } else {
        // 唤醒微信支付
        wx.requestPayment({
          timeStamp: response.timeStamp,
          nonceStr: response.nonceStr,
          package: response.package,
          signType: response.signType,
          paySign: response.paySign,
          success (res) { 
            Toast('支付成功')
            getList()
            setTimeout(function(){
              _this.setData({
                payLoading: false
              })
            }, 1500)
          },
          fail (res) {
            Toast('支付失败')
            setTimeout(function(){
              _this.setData({
                payLoading: false
              })
            }, 1500)
          }
        })
      }
    }).catch(err => {
      Toast(err)
      setTimeout(function(){
        _this.setData({
          payLoading: false
        })
      }, 1500)
    })
  },

  // 删除订单
  deleteOrder(){
    const { d: order_id } = getOptions()
    const { getList } = this
    Dialog.confirm({
      message: '确定删除订单？',
      confirmButtonText: '确定',
      cancelButtonText: '取消'
    }).then(() => {
      // on confirm
      changeOrderStatus({ order_id, order_status: -2 }).then(res => {
        wx.navigateBack()
        Toast('删除成功')
      }).catch(err => Toast(err))
    })
    .catch(() => {
      // on cancel
    });
  },

  // 确认收货
  confirmOrder(){
    const { d: order_id } = getOptions()
    const { getList } = this
    Dialog.confirm({
      message: '确定已收货？',
      confirmButtonText: '确定',
      cancelButtonText: '取消'
    }).then(() => {
      // on confirm
      changeOrderStatus({ order_id, order_status: 5 }).then(res => {
        getList()
        Toast('收货成功')
      }).catch(err => Toast(err))
    })
    .catch(() => {
      // on cancel
    });
  },

  // 查看物流
  viewLogis(e){
    const { d } = getOptions()
    wx.navigateTo({
      url: '/pages/logist/logist?d=' + d
    })
  }
  
})