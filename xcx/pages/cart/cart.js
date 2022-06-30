import { getCartList, deleteOneFromCart, addGoodsCart, createOrder, getAddressList } from '../../utils/api'
import Toast from '@vant/weapp/toast/toast'
import Dialog from '@vant/weapp/dialog/dialog'
const app = getApp()

Page({

  // 页面的初始数据
  data: {
    checkedAll: false,
    checkList: '',
    list: [],
    addressList: [],
    total: 0
  },

  // 生命周期函数--监听页面加载
  onShow: function () {
    const _this = this
    const wx_openid = app.globalData.openid
    if (wx_openid) {
      this.getList()
      // 判断是否有地址
      getAddressList({ wx_openid }).then(res => {
        _this.setData({
          addressList: res
        })
      })
    } else {
      Dialog.confirm({
        title: '提示',
        message: '您还未登录，去授权登录？',
      }).then(() => {
        // on close
        wx.navigateTo({
          url: '/pages/login/login'
        })
      }).catch(() => {
        // on cancel
      });
    }
  },

  // 下拉刷新
  onPullDownRefresh: function() {
    this.getList()
  },

  // 初始化数据
  getList(){
    const _this = this
    const wx_openid = app.globalData.openid
    getCartList({ wx_openid }).then(resp => {
      const cartList = resp.list.map(item => { return Object.assign(item, { minNum: parseInt(item.min_buy_num), cover_img: item.cover_img.split(',')[0]}) })
      _this.setData({
        list: cartList
      }, () => {
        _this.calcueTotal()
      })
    }).catch(err => Toast(err))
    wx.stopPullDownRefresh()
  },

  // changeAll(e){
  //   const { list } = this.data
  //   this.setData({
  //     checkedAll: e.detail,
  //     checkList: e.detail ? list.map(item => item.shop_cart_id) : []
  //   }, function(){
  //     this.calcueTotal()
  //   })
  // },

  // 选择单个商品
  singleChange(e){
    this.setData({
      checkList: e.detail
    }, function(){
      this.calcueTotal()
    })
  },

  // 删除单个商品
  delete(event){
    const wx_openid = app.globalData.openid
    const { id: shop_cart_ids } = event.currentTarget.dataset
    const { position, instance } = event.detail;
    switch (position) {
      case 'left':
      case 'cell':
        instance.close()
        break
      case 'right':
        Dialog.confirm({
          message: '确定删除吗？',
        }).then(() => {
          deleteOneFromCart({wx_openid, shop_cart_ids}).then(res => {
            Toast('删除成功')
            this.getList()
          }).catch(err => Toast(err))
          instance.close()
        }).catch(() => {
          instance.close()
        })
        break
    }
  },

  // 去下单
  goPurchase() {
    const { list, checkList, addressList } = this.data
    const wx_openid = app.globalData.openid

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

    if (checkList.length===0) {
      Toast('请选择要购买的商品')
      return false
    }

    Toast.loading({
      mask: true,
      message: '下单中...',
    })
    // 处理商品数组
    const goods_list = list.filter(item => item.shop_cart_id === checkList).map(goods => { return {goods_id: goods.goods_id, goods_count: goods.goods_count} })
    // const goods_list = checkList
    //   .map(id => list.find(item => item.shop_cart_id === id))
    //   .map(goods => { return {goods_id: goods.goods_id, goods_count: goods.goods_count} })
    createOrder({wx_openid, goods_list: JSON.stringify(goods_list)}).then(res => {
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
      Toast(err)
      Toast.clear()
    })
  },

  // 改变商品的数量
  onChangeNum(e){
    const _this = this
    const wx_openid = app.globalData.openid
    const index = e.currentTarget.dataset.ind
    const goodsID = e.currentTarget.dataset.gd
    const up = 'list[' + index + '].goods_count' 
    addGoodsCart({ wx_openid, goods_id: goodsID, goods_count: e.detail }).then(res => {
      _this.getList()
    })
    // this.setData({
    //   [up]: e.detail
    // },function(){
    //   this.calcueTotal()
    // })
  },

  // 计算总价
  calcueTotal(){
    const { list, checkList } = this.data
    let totalMoney = 0
    if (checkList.length) {
      // totalMoney = checkList
      //   .map(id => list.find(item => item.shop_cart_id === id))
      //   .map(goods => Number(goods.goods_price) * Number(goods.goods_count || 1))
      //   .reduce((prev, cur) => prev + cur)
      totalMoney = list.filter(item => item.shop_cart_id === checkList).map(goods => Number(goods.goods_price) * Number(goods.goods_count || 1))[0]
    }
    this.setData({
      total: totalMoney * 100
    })
  }

})