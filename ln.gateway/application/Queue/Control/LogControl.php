<?php

namespace Queue\Control;

use Common\Facades\AppClusterRedisFacade as ClusterRedis;
use Init\Control\ApiControl;
use MrStock\System\Helper\Config;
use MrStock\System\MJC\Control;
use MrStock\System\MJC\Http\Request;
use MrStock\System\Orm\Model;

class LogControl extends Control
{
    /**
     * @OpDescription(whatFor="后台日志入库",codeMonkey="")
     */
    public function addOp(Request $request,Model $model)
    {

        while (1){
            $paths = Config::get("serviceSite");
            $path_prefix = Config::get("path_prefix");
            $date = date('Ymd');
            foreach ($paths as $key => $path){
                $filename = $path_prefix.'.'.$key.'_public_.._application__ROUTERECORD_'.$date.'.log';
                if (!file_exists($filename)){
                    continue;
                }
                $fp = new \SplFileObject($filename, 'rb');
                $startLine = 1;
                $re = $model->table('file_start_line')->where(['file_path'=>$filename])->find();
                if (!empty($re)){
                    $startLine = $re['start_line'];
                }
                $fp->seek($startLine-1);// 转到第N行, seek方法参数从0开始计数
                $insertDataList = [];
                while (1){
                    $content=$fp->current();// current()获取当前行内容
                    if (!empty($content)){
                        $content = json_decode($content,true);
                        $insertData = [];
                        $insertData['itime'] = $content['time'];
                        $insertData['request'] = $content['request'];
                        $insertData['rpc_msg_id'] = $content['rpc_msg_id'];
                        $insertData['apicode'] = $content['apicode'];
                        $insertData['result'] = $content['message'];
                        $insertData['result_code'] = json_decode($content['message'],true)['code'] ?: '';
                        $insertData['result_message'] = json_decode($content['message'],true)['message'] ?: '';
                        $insertData['result_data'] = '' ?: '';
                        $insertData['login_ip'] = $content['ip'];
                        $insertData['runtime'] = $content['runtime'];
                        $insertData['c'] = $content['c'];
                        $insertData['a'] = $content['a'];
                        $insertData['v'] = $content['v'];
                        $insertData['site'] = $key;
                        $insertData['admin_id'] = $content['admin_id'];
                        $insertDataList[] = $insertData;
                        $startLine++;
                        $fp->next();// 下一行
                    }else{
                        break;
                    }

                }
                $model->beginTransaction();
                if (!empty($re)){
                    $re = $model->table('file_start_line')->where(['file_path'=>$filename])->update(['start_line'=>$startLine]);
                }else{
                    $re = $model->table('file_start_line')->insert(['file_path'=>$filename,'start_line'=>$startLine]);
                }
                if (!$re){
                    $model->rollback();
                    continue;
                }
                if (!empty($insertDataList)){
                    $re = $model->table('admin_log')->insertAll($insertDataList);
                    if (!$re){
                        $model->rollback();
                        continue;
                    }
                }
                $model->commit();
            }
            sleep(1);
            echo 'sleep 1s';
            echo "\n";
        }

    }




}