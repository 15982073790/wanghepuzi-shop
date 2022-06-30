/*
 * @Author: your name
 * @Date: 2020-07-17 16:35:11
 * @LastEditTime: 2020-08-27 15:31:48
 * @LastEditors: Please set LastEditors
 * @Description: In User Settings Edit
 * @FilePath: \cloud.manager\src\utils\request.js
 */
import Vue from 'vue'
import axios from 'axios'
import store from '@/store'
// import storage from 'store'
import qs from 'qs'
import notification from 'ant-design-vue/es/notification'
import { VueAxios } from './axios'
import { ACCESS_TOKEN, ADMIN_ID } from '@/store/mutation-types'

// 创建 axios 实例
const request = axios.create({
  // API 请求的默认前缀
  baseURL: process.env.VUE_APP_API_BASE_URL,
  timeout: 6000 // 请求超时时间
})

// 异常拦截处理器
const errorHandler = (error) => {
  if (error.response) {
    const data = error.response.data
    const token = Vue.ls.get(ACCESS_TOKEN)
    if (error.response.status === 403) {
      notification.error({
        message: 'Forbidden',
        description: data.message
      })
    }
    if (error.response.status === 401 && !(data.result && data.result.isLogin)) {
      notification.error({
        message: 'Unauthorized',
        description: 'Authorization verification failed'
      })
      if (token) {
        store.dispatch('Logout').then(() => {
          setTimeout(() => {
            window.location.reload()
          }, 1500)
        })
      }
    }
  }
  return Promise.reject(error)
}

// request interceptor
request.interceptors.request.use(config => {
  const token = Vue.ls.get(ACCESS_TOKEN)
  const adminId = Vue.ls.get(ADMIN_ID)
  if (token) {
    config.data = Object.assign({ key_token: token, admin_id: adminId }, config.data) // 让每个请求携带自定义 token 请根据实际情况自行修改
  }
  config.data = qs.stringify(config.data)
  return config
}, errorHandler)

// response interceptor
request.interceptors.response.use((response) => {
  if (response.data.code === 1) {
    return response.data
  } else if (response.data.code === '-1003') {
    notification.error({
      message: '登录失效',
      description: '请重新登录'
    })
    store.dispatch('Logout').then(() => {
      setTimeout(() => {
        window.location.reload()
      }, 1500)
    })
    // setTimeout(() => {
    //   window.location.reload()
    // }, 1500)
  } else {
    notification.error({
      message: '错误',
      description: response.data.message
    })
  }
}, errorHandler)

const installer = {
  vm: {},
  install (Vue) {
    Vue.use(VueAxios, request)
  }
}

export default request

export {
  installer as VueAxios,
  request as axios
}
