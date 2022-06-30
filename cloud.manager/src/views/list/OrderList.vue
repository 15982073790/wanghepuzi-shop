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
            <a-form-model-item label="商品名称" prop="goods_name">
              <a-input v-model="queryParam.goods_name" placeholder="请输入商品名称" />
            </a-form-model-item>
          </a-col>
          <a-col :md="6" :sm="24">
            <a-form-model-item label="订单编号" prop="order_sn">
              <a-input v-model="queryParam.order_sn" placeholder="请输入支付编号" />
            </a-form-model-item>
          </a-col>
          <!-- <a-col :md="6" :sm="24">
            <a-form-model-item label="支付编号" prop="pay_sn">
              <a-input v-model="queryParam.pay_sn" placeholder="请输入支付编号" />
            </a-form-model-item>
          </a-col> -->
          <a-col :md="6" :sm="24">
            <a-form-model-item label="用户名/手机号" prop="user_name_tel">
              <a-input v-model="queryParam.user_name_tel" placeholder="请输入用户名/手机号" />
            </a-form-model-item>
          </a-col>
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
            <a-form-model-item label="创建时间" prop="itime">
              <a-range-picker
                v-model="queryParam.itime"
                style="width: 100%"
                format="YYYY-MM-DD"
              />
            </a-form-model-item>
          </a-col> -->
          <a-col :md="6" :sm="24">
            <span class="table-page-search-submitButtons">
              <a-button type="primary" @click="$refs.table.refresh(true)">查询</a-button>
              <a-button style="margin-left: 8px" @click="reset">重置</a-button>
              <a-button style="margin-left: 8px" @click="$refs.table.refresh()">刷新</a-button>
              <a-button style="margin-left: 8px" @click="exportExcel" icon="cloud-download">导出</a-button>
            </span>
          </a-col>
        </a-row>
      </a-form-model>
    </div>
    <s-table
      ref="table"
      size="default"
      row-key="order_id"
      :scroll="{ x: 2000 }"
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
      <template slot="pay_sn" slot-scope="pay_sn">
        <span>{{ pay_sn || '-' }}</span>
      </template>
      <template slot="status" slot-scope="text">
        <span v-if="text==='1'">启用</span>
        <span v-else>停用</span>
      </template>
      <template slot="waybill_number" slot-scope="text, scope">
        <span v-if="text">{{ scope.shipper_code | codeFilter }}<br>{{ text }}</span>
        <span v-else>-</span>
      </template>
      <span slot="action" slot-scope="text, scope">
        <a @click="viewOrder(scope)">查看</a>
        <a-divider v-if="scope.order_status === '2' || scope.order_status === '4'" type="vertical" />
        <a v-if="scope.order_status === '2' || scope.order_status === '4'" @click="showFaModal(scope)">{{ scope.waybill_number ? '修改物流' : '发货' }}</a>
        <a-divider v-if="scope.order_status === '2'" type="vertical" />
        <a-popconfirm
          :title="`订单号${scope.order_sn},确定退款?`"
          ok-text="确定"
          cancel-text="取消"
          @confirm="tui(scope)"
        >
          <a v-if="scope.order_status === '-3'">退款</a>
        </a-popconfirm>
      </span>
    </s-table>
    <a-modal v-model="showInfoModal" title="查看订单" :footer="null" width="1200px">
      <a-descriptions title="订单信息">
        <a-descriptions-item label="订单编号">{{ mdl.order_sn | fomateNotext }}</a-descriptions-item>
        <a-descriptions-item label="支付编号">{{ mdl.pay_sn | fomateNotext }}</a-descriptions-item>
        <a-descriptions-item label="订单状态"><a>{{ mdl.order_status | statusFilter }}</a></a-descriptions-item>
        <a-descriptions-item label="用户名">{{ mdl.user_name | fomateNotext }}</a-descriptions-item>
        <a-descriptions-item label="手机号">{{ mdl.tel | fomateNotext }}</a-descriptions-item>
        <a-descriptions-item label="下单时间">{{ mdl.utime | formateTime }}</a-descriptions-item>
        <a-descriptions-item label="优惠金额">{{ mdl.total_coupon_price }} 元</a-descriptions-item>
        <a-descriptions-item label="单价">{{ mdl.goods_prices }} 元</a-descriptions-item>
        <a-descriptions-item label="商品数">{{ mdl.goods_counts }}</a-descriptions-item>
        <a-descriptions-item label="运费">{{ mdl.total_area_price }} 元</a-descriptions-item>
        <a-descriptions-item label="总金额">{{ mdl.total_pay_money }} 元</a-descriptions-item>
      </a-descriptions>
      <a-divider style="margin-bottom: 32px"/>
      <a-descriptions title="物流信息">
        <a-descriptions-item label="物流单号">{{ mdl.waybill_number | fomateNotext }}</a-descriptions-item>
        <a-descriptions-item label="物流品牌">{{ mdl.shipper_code | codeFilter }}</a-descriptions-item>
      </a-descriptions>
      <p>收货地址:  {{ mdl.address }}</p>
    </a-modal>
    <a-modal v-model="faModal" title="发货" @ok="handleOk" okText="确定" cancelText="取消">
      <a-form-model ref="faForm" :model="form" :rules="rules" :label-col="labelCol" :wrapper-col="wrapperCol">
        <a-form-model-item label="物流公司" prop="shipper_code">
          <a-select v-model="form.shipper_code" placeholder="请选择物流公司">
            <a-select-option value="STO">申通快递</a-select-option>
            <a-select-option value="YTO">圆通速递</a-select-option>
            <a-select-option value="HTKY">百世快递</a-select-option>
            <a-select-option value="HHTT">天天快递</a-select-option>
          </a-select>
        </a-form-model-item>
        <a-form-model-item label="物流单号" prop="waybill_number" placeholder="请输入物流单号">
          <a-input v-model="form.waybill_number" :maxLength="25"/>
        </a-form-model-item>
      </a-form-model>
    </a-modal>
  </a-card>
</template>

<script>
import Vue from 'vue'
import qs from 'qs'
// import moment from 'moment'
import { STable } from '@/components'
import { ACCESS_TOKEN, ADMIN_ID } from '@/store/mutation-types'
import { getOrderList, faLogis, tuiMoney, updateOrderStatus } from '@/api/list'

export default {
  name: 'TableList',
  components: {
    STable
  },
  data () {
    return {
      roleType: Number(Vue.ls.get('ROLE_TYPE')),
      description: '列表使用场景：后台管理中的权限管理以及角色管理，可用于基于 RBAC 设计的角色权限控制，颗粒度细到每一个操作类型。',
      // 查询参数
      queryParam: {
        goods_name: '',
        order_sn: '',
        user_name_tel: '',
        // itime: [],
        order_status: '0'
      },
      // 表头
      columns: [
         {
          title: '序号',
          align: 'center',
          width: 80,
          fixed: 'left',
          customRender: (text, record, index) => index + 1
        },
        {
          title: '订单编号',
          align: 'center',
          width: 200,
          dataIndex: 'order_sn'
        },
        {
          title: '商品名',
          align: 'center',
          dataIndex: 'goods_names'
        },
        {
          title: '商品编号',
          align: 'center',
          dataIndex: 'goods_sns'
        },
        {
          title: '支付编号',
          align: 'center',
          width: 300,
          dataIndex: 'pay_sn',
          scopedSlots: { customRender: 'pay_sn' }
        },
        {
          title: '用户昵称',
          align: 'center',
          dataIndex: 'user_name'
        },
        {
          title: '手机号',
          align: 'center',
          dataIndex: 'tel'
        },
        {
          title: '物流',
          align: 'center',
          dataIndex: 'waybill_number',
          scopedSlots: { customRender: 'waybill_number' }
        },
        {
          title: '总金额(元)',
          align: 'center',
          dataIndex: 'total_pay_money'
        },
        {
          title: '订单状态',
          align: 'center',
          dataIndex: 'order_status_name'
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
          width: 180,
          dataIndex: 'action',
          scopedSlots: { customRender: 'action' }
        }
      ],
      data: [],
      areaList: [],
      // 查看详情
      showInfoModal: false,
      mdl: {},
      // 发货
      faModal: false,
      form: {
        shipper_code: '',
        waybill_number: ''
      },
      labelCol: { span: 4 },
      wrapperCol: { span: 18 },
      rules: {
        shipper_code: [{ required: true, message: '请选择物流公司', trigger: 'blur' }],
        waybill_number: [{ required: true, message: '请输入物流单号', trigger: 'blur' }]
      }
    }
  },
  filters: {
    statusFilter (status) {
      if (status === '-1') {
        return '已取消'
      } else if (status === '1') {
        return '待支付'
      } else if (status === '-2') {
        return '已删除'
      } else if (status === '2') {
        return '待发货'
      } else if (status === '-3') {
        return '退款中'
      } else if (status === '3') {
        return '已退款'
      } else if (status === '4') {
        return '待收货'
      } else if (status === '5') {
        return '已完成'
      }
      return '-'
    },
    codeFilter (code) {
      const obj = {
        STO: '申通快递',
        YTO: '圆通速递',
        HTKY: '百世快递',
        HHTT: '天天快递'
      }
      return obj[code]
    },
    fomateNotext (text) {
      return text || '-'
    }
  },
  methods: {
    tui (scope) {
      const { order_id: orderID } = scope
      tuiMoney({ order_id: orderID }).then(res => {
        if (res && res.code === 1) {
          this.$refs.table.refresh()
          this.$message.success('退款成功')
        }
      }).catch(err => this.$message.error(err))
    },
    reset () {
      this.$refs.form.resetFields()
      this.$refs.table.refresh(true)
    },
    getList (parameter) {
      parameter = Object.assign(parameter, this.queryParam)
      // const { itime } = parameter
      // if (itime && itime.length > 0) {
      //   parameter.itime_start = moment(moment(itime[0]).format('YYYY-MM-DD 00:00:00')).valueOf() / 1000
      //   parameter.itime_end = moment(moment(itime[1]).format('YYYY-MM-DD 23:59:59')).valueOf() / 1000
      // } else {
      //   parameter.itime_start = ''
      //   parameter.itime_end = ''
      // }
      // delete parameter.itime
      // if (areaID.length) parameter.area_id = areaID[areaID.length - 1]
      return getOrderList(parameter).then(res => {
        return res.data
      })
    },
    exportExcel () {
      let url = ''
      const parameter = Object.assign({}, this.queryParam)
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
      parameter.isexport = 1
      const token = Vue.ls.get(ACCESS_TOKEN)
      const adminId = Vue.ls.get(ADMIN_ID)
      if (token) {
        url = qs.stringify(Object.assign({ key_token: token, admin_id: adminId }, parameter)) // 让每个请求携带自定义 token 请根据实际情况自行修改
      }
      window.open(process.env.VUE_APP_API_BASE_URL + '/?c=order&a=index&v=manager&site=goods&' + url)
    },
    viewOrder (scope) {
      this.showInfoModal = true
      this.mdl = scope
    },
    showFaModal (scope) {
      this.faModal = true
      this.mdl = scope
      if (scope.shipper_code && scope.waybill_number) {
        this.form = {
          waybill_number: scope.waybill_number,
          shipper_code: scope.shipper_code
        }
      }
    },
    handleOk () {
      const { mdl, form } = this
      this.$refs.faForm.validate(valid => {
        if (valid) {
          const { order_id: id } = mdl
          const { shipper_code: code, waybill_number: number } = form
          faLogis({
            order_id: id,
            shipper_code: code,
            waybill_number: number
          }).then(res => {
            if (res) {
              this.$message.success('发货成功')
              // 更改订单状态
              if (mdl.order_status !== '4') {
                updateOrderStatus({ order_id: id, order_status: '4' }).then(res => {
                  this.$refs.table.refresh()
                }).catch(() => {
                  this.$refs.table.refresh()
                })
              } else {
                this.$refs.table.refresh()
              }
              this.faModal = false
            }
          })
        }
      })
    }
  }
}
</script>
