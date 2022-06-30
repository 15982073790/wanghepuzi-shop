<?php
namespace Common\Logic;
use MrStock\System\MJC\Control;
use MrStock\System\Orm\Connector\Mysqli;
use MrStock\System\Helper\File;
use MrStock\System\Helper\Config;
use MrStock\System\Orm\Model;
use MrStock\System\MJC\Http\Request;
use MrStock\System\MJC\Validate;
use Common\Model\RoleModel;
use Common\Model\AdminroleModel;
use Common\Model\AdminModel;
use Common\Data\RoleData;
use MrStock\System\Helper\Arr;
//服务
class AdminroleLogic extends Control{


    public function selectrole($request,$curpage,$pagesize,$where=[],$order="")
    {
     $where=empty($where)?[]:$where;
     $order=empty($order)?"itime desc":$order;
     if (!empty($request['role_name'])){
         $where['role_name'] = $request['role_name'];
     }
     if (!empty($request['status'])){
         $where['status'] = $request['status'];
     }
      $appmodel = new Model("role");
      $totalNum = $appmodel->where($where)->count();////总条数

      $dataList = $appmodel->where($where)->order($order)->page($curpage,$pagesize)->select();

      return ['list'=>$dataList,'total_num'=>$totalNum];
     

    }
}