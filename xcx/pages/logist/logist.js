// pages/logist/logist.js
import { getLogistic } from '../../utils/api'
import { getOptions } from '../../utils/util'
import Toast from '@vant/weapp/toast/toast';
Page({

  /**
   * 页面的初始数据
   */
  data: {
    active: 0,
    shipper: '',
    code: '',
    steps: []
  },
  onShow(){
    this.getLogisticInfo()
  },
  // 获取物流信息
  getLogisticInfo(){
    const _this = this
    const options = getOptions()
    getLogistic({ order_id: options.d}).then(res => {
      console.log(res)
      const steps = res.data.map(item => {
        return {
          text: item.context,
          desc: item.ftime
        }
      })
      _this.setData({
        steps,
        code: res.nu,
        shipper: res.com
      })
    })
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