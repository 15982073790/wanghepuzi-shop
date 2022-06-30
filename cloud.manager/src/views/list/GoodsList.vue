<template>
  <a-card :bordered="false">
    <div class="table-page-search-wrapper">
      <a-form-model
        ref="form"
        :model="queryParam"
        layout="inline"
        :label-col="{span: 6}"
        :wrapper-col="{span:18}"
        label-align="right"
      >
        <a-row :gutter="24">
          <a-col :md="6" :sm="24">
            <a-form-model-item label="商品名或编号" prop="goods_name_sn">
              <a-input v-model="queryParam.goods_name_sn" placeholder="请输入商品名或编号" />
            </a-form-model-item>
          </a-col>
          <!-- <a-col :md="6" :sm="24">
            <a-form-model-item label="绑定时间" prop="user_itime">
              <a-range-picker
                v-model="queryParam.user_itime"
                style="width: 100%"
                format="YYYY-MM-DD"
              />
            </a-form-model-item>
          </a-col> -->
          <a-col :md="6" :sm="24">
            <span class="table-page-search-submitButtons">
              <a-button type="primary" @click="$refs.table.refresh(true)">查询</a-button>
              <a-button style="margin-left: 8px" @click="$refs.table.refresh()">刷新</a-button>
              <a-button style="margin: 0 8px" @click="reset">重置</a-button>
              <a-button type="primary" @click="showModal()">新增</a-button>
              <!-- <a-button style="margin-left: 8px" @click="exportExcel" icon="cloud-download">导出</a-button> -->
            </span>
          </a-col>
        </a-row>
      </a-form-model>
    </div>
    <s-table
      ref="table"
      size="default"
      :scroll="{x: 2000}"
      row-key="goods_id"
      :columns="columns"
      :data="getList"
      show-pagination="auto"
    >
      <template slot="goods_name" slot-scope="goods_name">
        <span class="title" :title="goods_name">{{ goods_name || '-' }}</span>
      </template>
      <template slot="itime" slot-scope="text">
        <span>{{ text | formateTime }}</span>
      </template>
      <template slot="puser_name" slot-scope="puser_name">
        <span>{{ puser_name || '-' }}</span>
      </template>
      <template slot="coupon_status" slot-scope="coupon_status, scope">
        <!-- <span>{{ coupon_status == '2' ? '是' : '-' }}</span> -->
        <a-switch :checked="coupon_status == '2' ? true : false" checked-children="开" un-checked-children="关" @change="changeCoupon(scope)" />
      </template>
      <template slot="banner_status" slot-scope="banner_status, scope">
        <a-switch :checked="banner_status == '2' ? true : false" checked-children="是" un-checked-children="否" @change="changeBanner(scope)" />
      </template>
      <template slot="publish_status" slot-scope="publish_status, scope">
        <a-switch :checked="publish_status == '2' ? true : false" checked-children="上架" un-checked-children="下架" @change="changePublish(scope)" />
      </template>
      <template slot="quality_goods_status" slot-scope="quality_goods_status, scope">
        <a-switch :checked="quality_goods_status == '2' ? true : false" checked-children="是" un-checked-children="否" @change="changeQuality(scope)" />
      </template>
      <template slot="status" slot-scope="text">
        <span v-if="text==='1'">启用</span>
        <span v-else>停用</span>
      </template>
      <template slot="action" slot-scope="text, scope">
        <a @click="showModal(scope)">编辑</a>
      </template>
    </s-table>
    <a-modal v-model="addGoodsModal" :title="modalTitle" @ok="handleOk" okText="确定" cancelText="取消">
      <a-form-model ref="addRoleForm" :model="form" :rules="rules" :label-col="labelCol" :wrapper-col="wrapperCol">
        <a-form-model-item label="姓名" prop="user_name">
          <a-input v-model="form.user_name" :maxLength="11"/>
        </a-form-model-item>
        <a-form-model-item label="手机号" prop="tel">
          <a-input v-model="form.tel" :maxLength="11"/>
        </a-form-model-item>
        <!-- <a-form-model-item label="地区" prop="area">
          <a-cascader v-model="form.area" :options="areaOptions" :load-data="loadData" placeholder="地区"/>
        </a-form-model-item> -->
        <a-form-model-item label="抵扣券" prop="coupon_money">
          <a-input-number :step="1" :min="0" :max="100000" :precision="2" v-model="form.coupon_money" />
        </a-form-model-item>
      </a-form-model>
    </a-modal>
  </a-card>
</template>

<script>
import Vue from 'vue'
import qs from 'qs'
import moment from 'moment'
import { STable } from '@/components'
import { ACCESS_TOKEN, ADMIN_ID } from '@/store/mutation-types'
import { getGoodsList, updateGoodStatus } from '@/api/list'

export default {
  name: 'TableList',
  components: {
    STable
  },
  data () {
    return {
      roleType: Number(Vue.ls.get('ROLE_TYPE')),
      description: '列表使用场景：后台管理中的权限管理以及角色管理，可用于基于 RBAC 设计的角色权限控制，颗粒度细到每一个操作类型。',
      mdl: {},
      // 查询参数
      queryParam: {
        goods_name_sn: ''
      },
      // 表头
      columns: [
         {
          title: '序号',
          align: 'center',
          fixed: 'left',
          customRender: (text, record, index) => index + 1
        },
        {
          title: '商品名',
          align: 'center',
          width: 300,
          fixed: 'left',
          dataIndex: 'goods_name',
          scopedSlots: { customRender: 'goods_name' }
        },
        {
          title: '剩余库存',
          align: 'center',
          dataIndex: 'residue_repertory_num'
        },
        {
          title: '已售',
          align: 'center',
          dataIndex: 'buy_num'
        },
        {
          title: 'Banner',
          align: 'center',
          dataIndex: 'banner_status',
          scopedSlots: { customRender: 'banner_status' }
        },
        {
          title: '精品',
          align: 'center',
          dataIndex: 'quality_goods_status',
          scopedSlots: { customRender: 'quality_goods_status' }
        },
        {
          title: '参与优惠',
          align: 'center',
          dataIndex: 'coupon_status',
          scopedSlots: { customRender: 'coupon_status' }
        },
        {
          title: '状态',
          align: 'center',
          dataIndex: 'publish_status',
          scopedSlots: { customRender: 'publish_status' }
        },
        {
          title: '排序',
          align: 'center',
          dataIndex: 'sort'
        },
        {
          title: '价格',
          align: 'center',
          dataIndex: 'goods_price'
        },
        {
          title: '原价',
          align: 'center',
          dataIndex: 'goods_original_price'
        },
        {
          title: '商品编号',
          align: 'center',
          dataIndex: 'goods_sn'
        },
        {
          title: '型号',
          align: 'center',
          dataIndex: 'model'
        },
        {
          title: '规格',
          align: 'center',
          dataIndex: 'specification'
        },
        {
          title: '创建时间',
          dataIndex: 'itime',
          align: 'center',
          scopedSlots: { customRender: 'itime' }
        },
        {
          title: '操作',
          align: 'center',
          fixed: 'right',
          dataIndex: 'action',
          scopedSlots: { customRender: 'action' }
        }
      ],
      data: [],
      // 添加框
      labelCol: { span: 4 },
      wrapperCol: { span: 18 },
      addGoodsModal: false,
      modalTitle: '新增用户',
      form: {
        user_name: '',
        tel: '',
        area: [],
        coupon_money: 0
      },
      rules: {
        user_name: [{ required: true, message: '请输入姓名', trigger: 'blur' }],
        tel: [{ required: true, message: '请输入手机号', trigger: 'blur' }],
        area: [{ required: true, message: '请选择区域', trigger: 'blur' }]
      }
    }
  },
  created () {
    // getAreaList().then(res => {
    //   console.log('res', res)
    //   this.areaList = res.data
    // })
  },
  methods: {
    reset () {
      this.$refs.form.resetFields()
      this.$refs.table.refresh(true)
    },
    getList (parameter) {
      parameter = Object.assign(parameter, this.queryParam)
      // const { user_itime: userItime, area_id: areaID } = parameter
      // if (userItime && userItime.length > 0) {
      //   parameter.user_itime_start = moment(moment(userItime[0]).format('YYYY-MM-DD 00:00:00')).valueOf() / 1000
      //   parameter.user_itime_end = moment(moment(userItime[1]).format('YYYY-MM-DD 23:59:59')).valueOf() / 1000
      // } else {
      //   parameter.user_itime_start = ''
      //   parameter.user_itime_end = ''
      // }
      // delete parameter.user_itime
      // if (areaID.length) parameter.area_id = areaID[areaID.length - 1]
      return getGoodsList(parameter).then(res => {
        return res.data
      })
    },
    exportExcel () {
      let url = ''
      const parameter = Object.assign({}, this.queryParam)
      const { user_itime: userItime, area_id: areaID } = parameter
      if (userItime && userItime.length > 0) {
        parameter.user_itime_start = moment(moment(userItime[0]).format('YYYY-MM-DD 00:00:00')).valueOf() / 1000
        parameter.user_itime_end = moment(moment(userItime[1]).format('YYYY-MM-DD 23:59:59')).valueOf() / 1000
      } else {
        parameter.user_itime_start = ''
        parameter.user_itime_end = ''
      }
      delete parameter.user_itime
      if (areaID.length) parameter.area_id = areaID[areaID.length - 1]
      parameter.isexport = 1
      const token = Vue.ls.get(ACCESS_TOKEN)
      const adminId = Vue.ls.get(ADMIN_ID)
      if (token) {
        url = qs.stringify(Object.assign({ key_token: token, admin_id: adminId }, parameter)) // 让每个请求携带自定义 token 请根据实际情况自行修改
      }
      window.open('http://test.service.agent.topasst.com/?c=user&a=index&v=manager&site=useractivity&' + url)
    },
    handleOk () {
      console.log(123)
    },
    showModal (goods = null) {
      if (goods) {
        localStorage.setItem('goods', JSON.stringify(goods))
      } else { localStorage.removeItem('goods') }
      this.$router.push('/editGoods')
    },
    // 开启优惠券
    changeCoupon (scope) {
      console.log(scope)
      const { goods_id: goodsId, coupon_status: couponStatus } = scope
      updateGoodStatus({
        goods_id: goodsId,
        coupon_status: couponStatus === '1' ? 2 : 1
      }).then(res => {
        if (res) {
          this.$message.success(couponStatus === '1' ? '开启成功' : '关闭成功')
          this.$refs.table.refresh()
        }
      })
    },
    // banner
    changeBanner (scope) {
      console.log(scope)
      const { goods_id: goodsId, banner_status: bannerStatus } = scope
      updateGoodStatus({
        goods_id: goodsId,
        banner_status: bannerStatus === '1' ? 2 : 1
      }).then(res => {
        if (res) {
          this.$message.success(bannerStatus === '1' ? '开启成功' : '关闭成功')
          this.$refs.table.refresh()
        }
      })
    },
    changePublish (scope) {
      console.log(scope)
      const { goods_id: goodsId, publish_status: publishStatus } = scope
      updateGoodStatus({
        goods_id: goodsId,
        publish_status: publishStatus === '1' ? 2 : 1
      }).then(res => {
        if (res) {
          this.$message.success(publishStatus === '1' ? '上架成功' : '下架成功')
          this.$refs.table.refresh()
        }
      })
    },
    changeQuality (scope) {
      console.log(scope)
      const { goods_id: goodsId, quality_goods_status: qualityGoodsStatus } = scope
      updateGoodStatus({
        goods_id: goodsId,
        quality_goods_status: qualityGoodsStatus === '1' ? 2 : 1
      }).then(res => {
        if (res) {
          this.$message.success(qualityGoodsStatus === '1' ? '设置成功' : '取消成功')
          this.$refs.table.refresh()
        }
      })
    }
  }
}
</script>
