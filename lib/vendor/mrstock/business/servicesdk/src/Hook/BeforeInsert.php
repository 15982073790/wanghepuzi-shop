<?php
namespace MrStock\Business\ServiceSdk\Hook;

use MrStock\System\MJC\Http\Request;
use MrStock\System\Orm\Model;

class BeforeInsert
{

    /**
     * 集中处理 insert insertall 公共字段
     * 服务自动加入appcode
     *
     * @param Request $request            
     * @param unknown $data            
     * @return unknown
     */
    public function run(Request $request, $params)
    {
        if ($request->ignoreBeforeInsert) {
            return;
        }
        list ($data, $options, $host) = $params;
        $tmpTables = $options['table'];
        $tables = explode(',', $tmpTables);
        $table = array_shift($tables);
        $model = new Model(null, $host);
        $table = $model->showColumns($table);
        $fields = array_keys($table);
        
        if (in_array('appcode', $fields)) {
            if (! isset($data['appcode'])) {
                $data['appcode'] = $request->appcode;
            }
            if (! isset($data['client'])) {
                $data['client'] = $request->client ? $request->client : $request->server['HTTP_USER_AGENT'];
            }
            if(!$data['client'] || $data['client']==null){
                $data['client']='';
            }
            if (! isset($data['itime'])) {
                $data['itime'] = time();
            }
            if (! isset($data['utime'])) {
                $data['utime'] = 0;
            }
            if (! isset($data['dtime'])) {
                $data['dtime'] = 0;
            }
            if (! isset($data['datastatus'])) {
                $data['datastatus'] = 1;
            }
        }
        return [$data,$options,$host
        ];
    }
}