import { getOptions } from '../../utils/util'
import { getOrderList, changeOrderStatus, openPay } from '../../utils/api'
import Toast from '@vant/weapp/toast/toast';
import Dialog from '@vant/weapp/dialog/dialog';
const app = getApp()

Page({

  // 页面的初始数据
  data: {
    active: 0,
    list: [],
    payLoading: false
  },

  onShow(){
    // const { t } = getOptions()
    var t = wx.getStorageSync('orderType')
    this.setData({
      active: t
    })
    this.getList(t)
  },

  // 下拉刷新
  onPullDownRefresh: function() {
    const { active } = this.data
    this.getList(active)
  },
  
  // 获取列表
  getList(status){
    const _this = this
    const wx_openid = app.globalData.openid
    const param = { wx_openid }
    // 全部订单的时候不用传状态值
    if (status !== '0') param.order_status = status
    getOrderList(param).then(res => {
      _this.setData({
        list: res ? res : []
      })
    }).catch(err => Toast(err))
    wx.stopPullDownRefresh()
  },

  onChange(e){
    const type = e.detail.name
    this.setData({
      active: type
    })
    wx.setStorageSync('orderType', type)
    this.getList(type)
  },

  // 取消订单
  cancelOrder(e){
    const order_id = e.currentTarget.dataset.d
    const { data: { active }, getList } = this
    Dialog.confirm({
      message: '确定取消订单？',
      confirmButtonText: '确定',
      cancelButtonText: '取消'
    })
      .then(() => {
        // on confirm
        changeOrderStatus({ order_id, order_status: -1 }).then(res => {
          getList(active)
          Toast('取消成功')
        }).catch(err => Toast(err))
      })
      .catch(() => {
        // on cancel
      });
  },

  // 取消订单
  postTui(e){
    const order_id = e.currentTarget.dataset.d
    const { data: { active }, getList } = this
    Dialog.confirm({
      message: '确定申请退款？',
      confirmButtonText: '确定',
      cancelButtonText: '取消'
    })
      .then(() => {
        // on confirm
        changeOrderStatus({ order_id, order_status: -3 }).then(res => {
          getList(active)
          Toast('申请成功')
        }).catch(err => Toast(err))
      })
      .catch(() => {
        // on cancel
      });
  },

   // 查看物流
   viewLogis(e){
    const { d } = e.currentTarget.dataset
    wx.navigateTo({
      url: '/pages/logist/logist?d=' + d
    })
  },

  // 立即支付
  pay(e){
    // 添加loading效果
    if (this.data.payLoading) {
      return false
    }
    this.setData({
      payLoading: true
    })
    const _this = this
    const { data: { active }, getList } = this
    const order_id = e.currentTarget.dataset.d
    const wx_openid = app.globalData.openid
    openPay({
      order_id,
      wx_openid
    }).then(response => {
      if (response && response.pay_success === 1) {
        // 不需要支付
        Toast('支付成功')
        getList(active)
        setTimeout(function(){
          _this.setData({
            payLoading: false
          })
        }, 1500)
      } else {
        wx.requestPayment({
          timeStamp: response.timeStamp,
          nonceStr: response.nonceStr,
          package: response.package,
          signType: response.signType,
          paySign: response.paySign,
          success (res) { 
            Toast('支付成功')
            getList(active)
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

  deleteOrder(e){
    const order_id = e.currentTarget.dataset.d
    const { data: { active }, getList } = this
    Dialog.confirm({
      message: '确定删除订单？',
      confirmButtonText: '确定',
      cancelButtonText: '取消'
    })
      .then(() => {
        // on confirm
        changeOrderStatus({ order_id, order_status: -2 }).then(res => {
          getList(active)
          Toast('删除成功')
        }).catch(err => Toast(err))
      })
      .catch(() => {
        // on cancel
      });
  },

  // 确认收货
  confirmOrder(e){
    const order_id = e.currentTarget.dataset.d
    const { data: { active }, getList } = this
    Dialog.confirm({
      message: '确定已收货？',
      confirmButtonText: '确定',
      cancelButtonText: '取消'
    })
      .then(() => {
        // on confirm
        changeOrderStatus({ order_id, order_status: 5 }).then(res => {
          getList(active)
          Toast('收货成功')
        }).catch(err => Toast(err))
      })
      .catch(() => {
        // on cancel
      });
  }

})