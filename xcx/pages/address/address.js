import { getAddressList, deleteAddress } from '../../utils/api'
import { getOptions } from '../../utils/util'
import Toast from '@vant/weapp/toast/toast'
import Dialog from '@vant/weapp/dialog/dialog'
const app = getApp()
Page({

  data: {
    list: []
  },

  onShow: function () {
    this.getList()
  },

  // 下拉刷新
  onPullDownRefresh: function() {
    this.getList()
  },

  toAdd(){
    wx.removeStorageSync('address') // 新增的时候要清除存储在本地的地址数据 
    wx.navigateTo({
      url: '/pages/addDress/addDress'
    })
  },

  getList(){
    const _this = this
    const wx_openid = app.globalData.openid
    getAddressList({wx_openid}).then(res => {
      _this.setData({
        list: res
      })
    })
    wx.stopPullDownRefresh()
  },

  // 选择地址编辑
  edit(event){
    const { id } = event.currentTarget.dataset
    const current = this.data.list.find(item => item.address_id === id)
    if (current) {
      // 保存到storage里
      wx.setStorage({
        key: 'address',
        data: JSON.stringify(current),
        success: function(){
          wx.navigateTo({
            'url': '/pages/addDress/addDress'
          })
        }
      })
    }
  },

  // 删除单个地址
  delete(event){
    const wx_openid = app.globalData.openid
    const { id: address_id } = event.currentTarget.dataset
    const { position, instance } = event.detail
    switch (position) {
      case 'left':
      case 'cell':
        instance.close()
        break
      case 'right':
        Dialog.confirm({
          message: '确定删除吗？',
        }).then(() => {
          deleteAddress({wx_openid, address_id}).then(res => {
            Toast('删除成功')
            this.getList()
          }).catch(err => {
            Toast(err)
          })
          instance.close()
        }).catch(() => {
          instance.close()
        })
        break
    }
  },

  // 选择地址
  selectAddress(e){
    const options = getOptions()
    const { id } = e.currentTarget.dataset
    // 判断是不是从下单那边过来
    if (!options.url) return false
    const address = this.data.list.find(item => item.address_id === id)
    // 保存到storage里
    wx.setStorage({
      key: 'selectAddress',
      data: JSON.stringify(address),
      success: function(){
        wx.navigateBack()
      }
    })
  }
})