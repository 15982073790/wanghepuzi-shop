<template>
  <a-card :bordered="false">
    <a-form-model ref="form" :model="form" :rules="rules" :label-col="labelCol" :wrapper-col="wrapperCol">
      <a-row>
        <a-col :span="12">
          <a-form-model-item label="商品名" prop="goods_name">
            <a-input v-model="form.goods_name" />
          </a-form-model-item>
        </a-col>
        <a-col :span="12">
          <a-form-model-item label="商品编号" prop="goods_sn">
            <a-input v-model="form.goods_sn" />
          </a-form-model-item>
        </a-col>
        <a-col :span="12">
          <a-form-model-item label="型号" prop="model">
            <a-input v-model="form.model" />
          </a-form-model-item>
        </a-col>
        <a-col :span="12">
          <a-form-model-item label="规格" prop="specification">
            <a-input v-model="form.specification" />
          </a-form-model-item>
        </a-col>
        <a-col :span="12">
          <a-form-model-item label="价格" prop="goods_price">
            <a-input-number :step="1" :min="0" :max="100000" :precision="2" v-model="form.goods_price" />
          </a-form-model-item>
        </a-col>
        <a-col :span="12">
          <a-form-model-item label="原价" prop="goods_original_price">
            <a-input-number :step="1" :min="0" :max="100000" :precision="2" v-model="form.goods_original_price" />
          </a-form-model-item>
        </a-col>
        <a-col :span="12">
          <a-form-model-item label="起订量" prop="min_buy_num">
            <a-input-number :step="1" :min="1" :max="1000000" :precision="0" v-model="form.min_buy_num" />
          </a-form-model-item>
        </a-col>
        <a-col :span="12">
          <a-form-model-item label="总库存" prop="repertory_num">
            <a-input-number :step="1" :min="0" :max="1000000" :precision="0" v-model="form.repertory_num" />
            <span style="margin-left:30px;">已售：{{ form.buy_num }}</span>
            <span style="margin-left:30px;">剩余库存：{{ form.residue_repertory_num }}</span>
          </a-form-model-item>
        </a-col>
        <a-col :span="12">
          <a-form-model-item label="销售量" prop="fake_buy_num">
            <a-input-number :step="1" :min="0" :max="1000000" :precision="0" v-model="form.fake_buy_num" />
          </a-form-model-item>
        </a-col>
        <a-col :span="12">
          <a-form-model-item label="预览手机号" prop="preview_tel">
            <a-input v-model="form.preview_tel" />
          </a-form-model-item>
        </a-col>
        <a-col :span="12">
          <a-form-model-item label="排序" prop="sort">
            <a-input-number :step="1" :min="0" :max="100000" :precision="0" v-model="form.sort" />
          </a-form-model-item>
        </a-col>
        <a-col :span="12">
          <a-form-model-item label="描述" prop="goods_describe">
            <a-input v-model="form.goods_describe" type="textarea" />
          </a-form-model-item>
        </a-col>
      </a-row>
      <a-form-model-item label="封面图(400*400)" prop="cover_img">
        <a-upload
          name="avatar"
          list-type="picture-card"
          class="avatar-uploader"
          :show-upload-list="false"
          :customRequest="customRequestCover"
          :before-upload="beforeUpload"
          @change="fengChange"
        >
          <img v-if="form.cover_img" :src="form.cover_img" alt="avatar" />
          <div v-else>
            <a-icon :type="loading ? 'loading' : 'plus'" />
            <div class="ant-upload-text">上传</div>
          </div>
        </a-upload>
      </a-form-model-item>
      <a-form-model-item label="首页推荐图(1380*600)">
        <a-upload
          name="avatar"
          list-type="picture-card"
          class="avatar-uploader"
          :show-upload-list="false"
          :customRequest="customRequestBanner"
          :before-upload="beforeUpload"
          @change="bannerChange"
        >
          <img v-if="form.banner_img" :src="form.banner_img" alt="avatar" />
          <div v-else>
            <a-icon :type="bannerloading ? 'loading' : 'plus'" />
            <div class="ant-upload-text">上传</div>
          </div>
        </a-upload>
      </a-form-model-item>
      <a-form-model-item label="详情页轮播图(1380*600)">
        <a-upload
          :customRequest="customRequestTopImg"
          list-type="picture-card"
          :file-list="topImgList"
          @preview="handlePreview"
          @change="handleChangeBanner"
        >
          <div v-if="topImgList.length < 20">
            <a-icon type="plus" />
            <div class="ant-upload-text">上传</div>
          </div>
        </a-upload>
      </a-form-model-item>
      <a-form-model-item label="详情图(1380*2000)">
        <a-upload
          :customRequest="customRequest"
          list-type="picture-card"
          :file-list="fileList"
          @preview="handlePreview"
          @change="handleChangeFile"
        >
          <div v-if="fileList.length < 20">
            <a-icon type="plus" />
            <div class="ant-upload-text">上传</div>
          </div>
        </a-upload>
      </a-form-model-item>
      <a-form-model-item label="运费">
        <a-table
          style="width: 500px"
          :columns="columns"
          :dataSource="data"
          :pagination="false"
          :loading="memberLoading"
        >
          <template slot="area_id" slot-scope="text, record">
            <!-- <a-cascader
              v-if="record.editable"
              :value="text"
              :options="areaOptions"
              :load-data="loadData"
              placeholder="地区"
              @change="(v, options) => areaChange(v, record.key, 'area', options)"
            /> -->
            <a-select v-if="record.editable" :value="text" placeholder="请选择区域" style="width: 120px" @change="v => areaChange(v, record.key)">
              <a-select-option v-for="(item, index) in areaOptions" :key="index" :value="item.area_id">{{ item.area_name }}</a-select-option>
            </a-select>
            <template v-else>{{ record.province_name || '-' }}</template>
          </template>
          <template slot="fee" slot-scope="text, record">
            <a-input-number
              v-if="record.editable"
              :value="text"
              :step="1"
              :min="0"
              :max="100000"
              :precision="2"
              @change="value => feiChange(value, record.key, 'fee')"
            />
            <template v-else>{{ text }}</template>
          </template>
          <template slot="operation" slot-scope="text, record">
            <template v-if="record.editable">
              <span v-if="record.isNew">
                <a @click="saveRow(record)">添加</a>
                <a-divider type="vertical" />
                <a-popconfirm title="是否要删除此行？" @confirm="remove(record.key)">
                  <a>删除</a>
                </a-popconfirm>
              </span>
              <span v-else>
                <a @click="saveRow(record)">保存</a>
                <a-divider type="vertical" />
                <a @click="cancel(record.key)">取消</a>
              </span>
            </template>
            <span v-else>
              <a @click="toggle(record.key)">编辑</a>
              <a-divider type="vertical" />
              <a-popconfirm title="是否要删除此行？" @confirm="remove(record.key)">
                <a>删除</a>
              </a-popconfirm>
            </span>
          </template>
        </a-table>
        <a-button style="width: 500px; margin-top: 16px; margin-bottom: 8px" type="dashed" icon="plus" @click="newMember">新增运费</a-button>
      </a-form-model-item>
      <a-form-model-item :wrapper-col="{ span: 14, offset: 4 }">
        <a-button type="primary" @click="onSubmit">提交</a-button>
        <a-button style="margin-left: 10px;">取消</a-button>
      </a-form-model-item>
      <a-modal :visible="previewVisible" :footer="null" @cancel="handleCancel">
        <img alt="example" style="width: 100%" :src="previewImage" />
      </a-modal>
    </a-form-model>
  </a-card>
</template>
<script>
// import Vue from 'vue'
// import qs from 'qs'
// import moment from 'moment'
// import { STable } from '@/components'
// import { ACCESS_TOKEN, ADMIN_ID } from '@/store/mutation-types'
import OSS from 'ali-oss'
import { getAreaList, uploadFileImg, addNewGoods, updateGoods } from '@/api/list'
function getBase64 (img, callback) {
  const reader = new FileReader()
  reader.addEventListener('load', () => callback(reader.result))
  reader.readAsDataURL(img)
}
export default {
  name: 'TableList',
  data () {
    return {
      goodID: 0,
      labelCol: { span: 4 },
      wrapperCol: { span: 14 },
      form: {
        goods_name: '',
        goods_sn: '',
        preview_tel: '',
        goods_price: 0,
        min_buy_num: 1,
        goods_original_price: 0,
        model: '',
        sort: 10,
        fake_buy_num: 0,
        repertory_num: 0,
        buy_num: 0,
        residue_repertory_num: 0,
        specification: '',
        goods_describe: '',
        banner_img: '',
        cover_img: ''
      },
      rules: {
        goods_name: [{ required: true, message: '请填写商品名称' }],
        goods_sn: [{ required: true, message: '请填写商品编号' }],
        goods_price: [{ required: true, message: '请填写商品价格' }],
        model: [{ required: true, message: '请填写商品型号' }],
        fake_buy_num: [{ required: true, message: '请填写销售量' }],
        min_buy_num: [{ required: true, message: '请填写起订量' }],
        repertory_num: [{ required: true, message: '请填写库存' }],
        specification: [{ required: true, message: '请填写商品规格' }],
        goods_original_price: [{ required: true, message: '请填写商品原价' }]
      },
      loading: false,
      bannerloading: false,
      imageUrl: '',
      // 上传图片
      previewVisible: false,
      previewImage: '',
      fileList: [],
      topImgList: [],
      // 运费
      memberLoading: false,
      columns: [
        {
          title: '地区',
          dataIndex: 'area_id',
          key: 'area_id',
          scopedSlots: { customRender: 'area_id' }
        },
        {
          title: '运费(元)',
          dataIndex: 'fee',
          key: 'fee',
          scopedSlots: { customRender: 'fee' }
        },
        {
          title: '操作',
          key: 'action',
          scopedSlots: { customRender: 'operation' }
        }
      ],
      // 运费数组
      data: [],
      areaOptions: []
    }
  },
  created () {
    getAreaList({ area_id: 100000 }).then(res => {
      this.areaOptions = res.data.list
      const goods = window.localStorage.getItem('goods')
      if (goods) {
        const good = JSON.parse(goods)
        this.goodID = good.goods_id
        this.form = {
          goods_name: good.goods_name,
          goods_sn: good.goods_sn,
          preview_tel: good.preview_tel,
          goods_price: parseFloat(good.goods_price),
          goods_original_price: parseFloat(good.goods_original_price),
          model: good.model,
          min_buy_num: parseInt(good.min_buy_num),
          sort: parseInt(good.sort),
          specification: good.specification,
          goods_describe: good.goods_describe,
          banner_img: good.banner_img,
          cover_img: good.cover_img,
          buy_num: parseInt(good.buy_num),
          residue_repertory_num: parseInt(good.residue_repertory_num),
          fake_buy_num: parseInt(good.fake_buy_num),
          repertory_num: parseInt(good.repertory_num)
        }
        this.topImgList = good.detail_top_img.split(',').map((item, index) => {
          return {
            name: '111.png',
            status: 'done',
            uid: index,
            url: item
          }
        })
        this.fileList = good.detail_img.split(',').map((item, index) => {
          return {
            name: '111.png',
            status: 'done',
            uid: index,
            url: item
          }
        })
        const areaPriceList = JSON.parse(good.area_price)
        this.data = areaPriceList.map((item, index) => {
          return {
            key: index + '',
            province_name: item.province_name,
            area_id: item.area_id,
            fee: item.area_price,
            editable: false
          }
        })
      } else {
        this.goodID = 0
        this.data = res.data.list.map((item, index) => {
          return {
            key: index + '',
            province_name: item.area_name,
            area_id: item.area_id,
            fee: 0,
            editable: false
          }
        })
      }
    })
  },
  methods: {
    loadData (selectedOptions) {
      const targetOption = selectedOptions[selectedOptions.length - 1]
      targetOption.loading = true
      getAreaList({ area_id: targetOption.value }).then(res => {
        const arr = res.data.list.map(item => {
          return {
            label: item.area_name,
            value: item.area_id
          }
        })
        targetOption.loading = false
        targetOption.children = arr
        this.areaOptions = [...this.areaOptions]
      })
    },
    onSubmit () {
      this.$refs.form.validate(valid => {
        if (valid) {
          const {
            goods_describe: goodsDescribe,
            goods_name: goodsName,
            goods_original_price: goodsOriginalPrice,
            goods_price: goodsPrice,
            goods_sn: goodsSn,
            preview_tel: previewTel,
            cover_img: coverImg,
            banner_img: bannerImg,
            fake_buy_num: fakeBuyNum,
            repertory_num: repertoryNum,
            min_buy_num: minBuyNum,
            model,
            sort,
            specification
          } = this.form
          // const bannerImg = this.bannerImgList.map(item => item.url).join(',')
          const detailTopImg = this.topImgList.map(item => item.url).join(',')
          const detailImg = this.fileList.map(item => item.url).join(',')
          const areaPrice = this.data.map(item => {
            return { area_price: item.fee, province_name: item.province_name, area_id: item.area_id }
          })
          const obj = {
            goods_describe: goodsDescribe,
            goods_name: goodsName,
            goods_original_price: goodsOriginalPrice.toFixed(2),
            goods_price: goodsPrice.toFixed(2),
            goods_sn: goodsSn,
            preview_tel: previewTel,
            fake_buy_num: fakeBuyNum,
            repertory_num: repertoryNum,
            min_buy_num: minBuyNum,
            model,
            sort,
            specification,
            detail_top_img: detailTopImg,
            banner_img: bannerImg,
            cover_img: coverImg,
            detail_img: detailImg,
            area_price: JSON.stringify(areaPrice)
          }
          if (this.goodID) {
            // 编辑
            updateGoods({ goods_id: this.goodID, ...obj }).then(res => {
              if (res) {
                this.$message.success('修改成功')
                this.$router.push('/goodsList')
              }
            })
          } else {
            // 新增
            addNewGoods(obj).then(res => {
              if (res) {
                this.$message.success('添加成功')
                this.$router.push('/goodsList')
              }
            })
          }
        }
      })
    },

    fengChange (info) {
      if (info.file.status === 'uploading') {
        this.loading = true
        return true
      }
      // if (info.file.status === 'done') {
      //   // Get this url from response in real world.
      //   getBase64(info.file.originFileObj, imageUrl => {
      //     this.imageUrl = imageUrl
      //     this.loading = false
      //   })
      // }
    },
    bannerChange (info) {
      if (info.file.status === 'uploading') {
        this.bannerloading = true
        return true
      }
      // if (info.file.status === 'done') {
      //   // Get this url from response in real world.
      //   getBase64(info.file.originFileObj, imageUrl => {
      //     this.imageUrl = imageUrl
      //     this.loading = false
      //   })
      // }
    },
    async customRequestCover (action) {
      const _this = this
      const file = action.file
      const res = await uploadFileImg()
      if (res && res.data) {
        const keyObj = res.data
        const client = new OSS({
          region: 'oss-cn-chengdu',
          accessKeyId: keyObj.AccessKeyId,
          secure: true,
          accessKeySecret: keyObj.AccessKeySecret,
          stsToken: keyObj.SecurityToken,
          bucket: 'cd-wanghepuzi'
        })
        const store = new Date().valueOf() + '' + parseInt(Math.random() * 10000) + file.name
        try {
          const result = await client.put(store, file)
          _this.$set(_this.form, 'cover_img', result.url)
        } catch (err) {
          console.log(err)
        }
      }
    },
    async customRequestBanner (action) {
      const _this = this
      const file = action.file
      const res = await uploadFileImg()
      if (res && res.data) {
        const keyObj = res.data
        const client = new OSS({
          region: 'oss-cn-chengdu',
          accessKeyId: keyObj.AccessKeyId,
          secure: true,
          accessKeySecret: keyObj.AccessKeySecret,
          stsToken: keyObj.SecurityToken,
          bucket: 'cd-wanghepuzi'
        })
        const store = new Date().valueOf() + '' + parseInt(Math.random() * 10000) + file.name
        try {
          const result = await client.put(store, file)
          _this.$set(_this.form, 'banner_img', result.url)
        } catch (err) {
          console.log(err)
        }
      }
    },
    async customRequestTopImg (action) {
      const _this = this
      const file = action.file
      action.onProgress()
      const res = await uploadFileImg()
      if (res && res.data) {
        const keyObj = res.data
        const client = new OSS({
          region: 'oss-cn-chengdu',
          accessKeyId: keyObj.AccessKeyId,
          secure: true,
          accessKeySecret: keyObj.AccessKeySecret,
          stsToken: keyObj.SecurityToken,
          bucket: 'cd-wanghepuzi'
        })
        const store = new Date().valueOf() + '' + parseInt(Math.random() * 10000) + file.name
        try {
          const result = await client.put(store, file)
          action.onSuccess()
          _this.$set(_this.topImgList, _this.topImgList.length - 1, {
            uid: new Date().valueOf(),
            name: result.name,
            status: 'done',
            url: result.url
          })
        } catch (err) {
          action.onError()
          console.log(err)
        }
      }
    },
    handleChangeBanner ({ file, fileList }) {
      if (file.status !== 'uploading') {
        console.log(file, fileList)
      }
      if (file.status === 'done') {
        this.$message.success('上传成功')
      } else if (file.status === 'error') {
        this.$message.error('上传失败')
      }
      this.topImgList = fileList
    },
     handleChangeFile ({ fileList }) {
      this.fileList = fileList
    },
    async customRequest (action) {
      const _this = this
      const file = action.file
      action.onProgress()
      const res = await uploadFileImg()
      if (res && res.data) {
        const keyObj = res.data
        const client = new OSS({
          region: 'oss-cn-chengdu',
          accessKeyId: keyObj.AccessKeyId,
          secure: true,
          accessKeySecret: keyObj.AccessKeySecret,
          stsToken: keyObj.SecurityToken,
          bucket: 'cd-wanghepuzi'
        })
        const store = new Date().valueOf() + '' + parseInt(Math.random() * 10000) + file.name
        try {
          const result = await client.put(store, file)
          action.onSuccess()
          _this.$set(_this.fileList, _this.fileList.length - 1, {
            uid: new Date().valueOf(),
            name: result.name,
            status: 'done',
            url: result.url
          })
        } catch (err) {
          action.onError()
        }
      }
    },
    beforeUpload (file) {
      const isJpgOrPng = file.type === 'image/jpeg' || file.type === 'image/png' || file.type === 'image/jpg'
      if (!isJpgOrPng) {
        this.$message.error('You can only upload JPG file!')
      }
      const isLt2M = file.size / 1024 / 1024 < 2
      if (!isLt2M) {
        this.$message.error('Image must smaller than 2MB!')
      }
      return isJpgOrPng && isLt2M
    },
    handleCancel () {
      this.previewVisible = false
    },
    async handlePreview (file) {
      if (!file.url && !file.preview) {
        file.preview = await getBase64(file.originFileObj)
      }
      this.previewImage = file.url || file.preview
      this.previewVisible = true
    },
    handleChange ({ fileList }) {
      // this.fileList = fileList
    },
    areaChange (value, key) {
      console.log(value, key)
      const newData = [...this.data]
      const target = newData.find(item => key === item.key)
      const options = this.areaOptions.find(item => value === item.area_id)
      if (target) {
        target.province_name = options.area_name
        target.area_id = options.area_id
        this.data = newData
      }
    },
    feiChange (value, key, column) {
      const newData = [...this.data]
      const target = newData.find(item => key === item.key)
      if (target) {
        target[column] = value
        this.data = newData
      }
    },
    saveRow (record) {
      console.log(record)
      this.memberLoading = true
      const { key, area_id: areaId, fee } = record
      if (!areaId) {
        this.memberLoading = false
        this.$message.error('请填写完整信息')
        return
      }
      const target = this.data.find(item => item.key === key)
      target.editable = false
      target.fee = Number(fee).toFixed(2)
      this.memberLoading = false
    },
    remove (key) {
      const newData = this.data.filter(item => item.key !== key)
      this.data = newData
    },
    toggle (key) {
      const target = this.data.find(item => item.key === key)
      target._originalData = { ...target }
      target.editable = !target.editable
    },
    getRowByKey (key, newData) {
      const data = this.data
      return (newData || data).find(item => item.key === key)
    },
    cancel (key) {
      const target = this.data.find(item => item.key === key)
      Object.keys(target).forEach(key => { target[key] = target._originalData[key] })
      target._originalData = undefined
    },
    newMember () {
      const length = this.data.length
      this.data.push({
        key: length === 0 ? '1' : (parseInt(this.data[length - 1].key) + 1).toString(),
        area: '',
        fee: 0,
        province_name: '',
        editable: false
      })
    }
  }
}
</script>
<style lang='less' scoped>
.avatar-uploader > .ant-upload {
  width: 128px;
  height: 128px;
}
.ant-upload-select-picture-card i {
  font-size: 32px;
  color: #999;
}

.ant-upload-select-picture-card .ant-upload-text {
  margin-top: 8px;
  color: #666;
}
/deep/ .ant-form-item img{
  width: 120px;
  height: auto;
}
</style>
