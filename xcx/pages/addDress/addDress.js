const app = getApp()
import area from '../../utils/area'
import { updateAddress, getArea } from '../../utils/api'
import Toast from '@vant/weapp/toast/toast';

Page({

  /**
   * 页面的初始数据
   */
  data: {
    address_id: '',
    username: '',
    phone: '',
    areaFull: '',
    province_id: '',
    city_id: '',
    county_id: '',
    address: '',
    isDefault: true,
    show: false, // 是否展示省市区弹框
    areaList: area
  },

  // 生命周期函数--监听页面显示
  onShow: function () {
    const _this = this
    wx.getStorage({
      key: 'address',
      success (res) {
        const address = JSON.parse(res.data)
        const { 
          province_name,
          city_name,
          county_name,
          address_id,
          true_name,
          address_tel,
          province_id,
          city_id,
          county_id,
          detail_address,
          address_default
        } = address
        _this.setData({
          address_id: address_id,
          username: true_name,
          phone: address_tel,
          areaFull: province_name + city_name + county_name,
          province_id: province_id,
          city_id: city_id,
          county_id: county_id,
          address: detail_address,
          isDefault: address_default === '2' ? true : false,
        })
      },
      fail(){
        _this.setData({
          address_id: '',
          username: '',
          phone: '',
          areaFull: '',
          province_id: '',
          city_id: '',
          county_id: '',
          address: '',
          isDefault: false,
        })
      }
    })
    // getArea().then(res => {
    //   console.log(res)
      // _this.setData({
      //   areaList: res
      // })
    // })
  },

  defaultChange({ detail }){
    this.setData({
      isDefault: detail
    })
  },

  // 展示省市区选择框
  showArea(){
    this.setData({
      show: true
    })
  },

  // 关闭省市区选择框
  onClose(){
    this.setData({
      show: false
    })
  },

  // 省市区选择框-确定
  handleConfirm(e){
    const address = e.detail.values
    this.setData({
      show: false,
      province_id: address[0].code,
      city_id: address[1].code,
      county_id: address[2].code,
      areaFull: address.map(item => item.name).join(' ')
    })
  },

  // 省市区选择框-取消
  handleCancel(){
    console.log('取消')
  },

  // 输入框事件 后期要加防抖
  onChange(e) {
    const key = e.currentTarget.dataset.k
    this.setData({
      [key]: e.detail
    })
  },

  // 点击按钮新增地址
  addAddress(){
    const { address_id, username, phone, areaFull, address, province_id, city_id, county_id, isDefault } = this.data

    if (!username){
      Toast('请填写用户名');
      return;
    }

    if (!phone || phone.length !== 11){
      Toast('请填写正确格式手机号');
      return;
    }

    if (!areaFull){
      Toast('选择区域');
      return;
    }

    if (!address || address.length < 6){
      Toast('请填写详细地址');
      return;
    }


    Toast.loading({
      mask: true,
      message: '添加中...',
    });

    const wx_openid = app.globalData.openid
    const addressObj = {
      true_name: username,
      address_tel: phone,
      detail_address: address,
      address_default: isDefault ? 2 : 1,
      wx_openid,
      province_id,
      city_id,
      county_id
    }
    // 如果有id 则是编辑
    if (address_id) addressObj.address_id = address_id
    updateAddress(addressObj).then(res => {
      Toast(address_id ? '编辑成功' : '添加成功')
      setTimeout(function(){
        //跳转回列表页
        wx.navigateBack({
          delta: 1
        })
      }, 1000)
    }).catch(err => Toast(err))
  }
})