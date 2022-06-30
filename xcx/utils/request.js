import conf from "../config.js"

const method = ['GET', 'POST', 'PUT', 'DELETE', 'OPTIONS', 'HEAD', 'TRACE', 'CONNECT'];

function prompt(e = {}, op, reject, resolve) {
  let title = e.message || '错误 ！';
  if (!title) return;
  let icon = 'success';
  if (e.code !== 1) {
    icon = 'none';
    reject(title, e.data)
  } else if (resolve) resolve(e.data);
  if (op.prompt !== false) wx.showToast({
    title,
    icon,
    duration: 2000
  })
}

export default (url = '', data={}, op = {}, type = 0) => {
  if (op.loading !== false) wx.showNavigationBarLoading();
  try{
    const token = wx.getStorageSync('token');
    if(token) data = Object.assign(data, { token });
    return new Promise((resolve, reject) => {
      wx.request({
        data,
        url: conf.request_url + url,
        method: method[type],
        header: {
          'content-type': 'application/x-www-form-urlencoded' //修改此处即可
        },
        success: (v) => prompt(v.data, op, reject, resolve),
        fail: (e) => prompt(e, op, reject),
        complete() {
          if (op.loading !== false) wx.hideNavigationBarLoading()
        }
      })
    })
  }catch(err){
    console.log(err);
  }
}