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
            <a-form-model-item label="状态" prop="order_status">
              <a-select v-model="queryParam.order_status" placeholder="请选择状态">
                <a-select-option value="0">全部</a-select-option>
                <a-select-option value="1">待支付</a-select-option>
                <a-select-option value="2">待发货</a-select-option>
                <a-select-option value="3">已退款</a-select-option>
                <a-select-option value="4">已发货</a-select-option>
                <a-select-option value="5">已完成</a-select-option>
                <a-select-option value="-3">退款中</a-select-option>
                <a-select-option value="-1">已取消</a-select-option>
              </a-select>
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
              <a-button style="margin-left: 8px" @click="reset">重置</a-button>
              <!-- <a-button style="margin-left: 8px" @click="exportExcel" icon="cloud-download">导出</a-button> -->
            </span>
          </a-col>
        </a-row>
      </a-form-model>
    </div>
    <s-table
      ref="table"
      size="default"
      row-key="goods_name"
      :columns="columns"
      :data="getList"
      show-pagination="auto"
    >
      <template slot="itime" slot-scope="text">
        <span>{{ text | formateTime }}</span>
      </template>
      <template slot="puser_name" slot-scope="puser_name">
        <span>{{ puser_name || '-' }}</span>
      </template>
      <template slot="status" slot-scope="text">
        <span v-if="text==='1'">启用</span>
        <span v-else>停用</span>
      </template>
    </s-table>
  </a-card>
</template>

<script>
import Vue from 'vue'
import qs from 'qs'
import moment from 'moment'
import { STable } from '@/components'
import { ACCESS_TOKEN, ADMIN_ID } from '@/store/mutation-types'
import { getFinanceList } from '@/api/list'

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
        order_status: '0'
      },
      // 表头
      columns: [
         {
          title: '序号',
          align: 'center',
          customRender: (text, record, index) => index + 1
        },
        // {
        //   title: '用户id',
        //   dataIndex: 'user_id'
        // },
        {
          title: '商品名',
          align: 'center',
          dataIndex: 'goods_name'
        },
        {
          title: '待支付',
          align: 'center',
          dataIndex: 'wait_pay'
        },
        {
          title: '取消支付',
          align: 'center',
          dataIndex: 'cancel_pay'
        },
        {
          title: '已支付',
          align: 'center',
          dataIndex: 'complete_pay'
        },
        {
          title: '退款中',
          dataIndex: 'itime',
          align: 'wait_refund'
        },
        {
          title: '已退款',
          dataIndex: 'complete_refund',
          align: 'center'
        },
        {
          title: '已发货',
          dataIndex: 'send_goods',
          align: 'center'
        },
        {
          title: '已完成',
          dataIndex: 'complete_goods',
          align: 'center'
        },
        {
          title: '总计',
          dataIndex: 'total_money',
          align: 'center'
        }
      ],
      data: [],
      areaList: []
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
      return getFinanceList(parameter).then(res => {
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
    }
  }
}
</script>
