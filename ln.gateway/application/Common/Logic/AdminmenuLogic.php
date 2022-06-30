<?php
namespace Common\Logic;
use MrStock\System\MJC\Control;
use MrStock\System\Orm\Model;
use MrStock\System\Helper\Arr;
use MrStock\System\Helper\File;
use MrStock\System\Orm\Connector\Mysqli;
use MrStock\System\MJC\Validate;
use Common\Helper\FunctionHelper;
use Common\Model\AdminroleModel;
class AdminmenuLogic extends Control{
 

    public function getadminmenu($request)
    {
      
        $result=[];
          $AdminroleModel=new AdminroleModel();
          $res=$AdminroleModel->gethasapi($request["admin_id"],[]);


          if(!empty($res)){
            $res=array_column($res, "apicode");
   
            $model=new Model("api_menu");
            $res=$model->field("sitename,groupname,cname,aname")->where(["apicode"=>["in",$res]])->limit(false)->select();

            $result=Arr::arrayToArrayKey($res, "sitename",1);

            foreach ($result as $key => &$value) {

              # code...
              $value=Arr::arrayToArrayKey($value, "groupname",1);
              foreach ($value as $key1 => &$value1) {
                $value1=Arr::arrayToArrayKey($value1, "cname",1);
                foreach ($value1 as $key2 => &$value2) {
                $value2=array_keys(Arr::arrayToArrayKey($value2, "aname",1));
              }
              }
            
            }
           
          }

        
        //end
        return $this->json(['list'=>$result]);
    }
      
  

}