<?php
namespace MrStock\Business\ServiceSdk\Hook;

use MrStock\System\MJC\Http\Request;
use MrStock\System\Orm\Model;

class BeforeUpdateDelete
{

    /**
     * 集中 update delete
     * 服务语句中加入 appcode 条防止与删除
     *
     * @param Request $request            
     * @param unknown $options            
     * @return string
     */
    public function run(Request $request, $params)
    {
        if ($request->ignoreBeforeUpdateDelete) {
            return;
        }
        list ($data, $options, $host) = $params;
        $tmpTables = $options['table'];
        $tables = explode(',', $tmpTables);
        $table = array_shift($tables);
        
        $model = new Model(null, $host);
        $table = $model->showColumns($table);
        $fields = array_keys($table);
        //本地兼容
        if(!$request->appcodelist){
            $request->appcodelist = $request->appcode;
        }
        if (in_array('appcode', $fields)) {
            $where = $options['where'];
            if (is_string($where)) {
                if (empty($where)) {
                    $where = ' WHERE 1=1 ';
                }
                $appcodes = explode(',', $request->appcodelist);
                foreach ($appcodes as $k => $v) {
                    $appcodes[$k] = "'" . $v . "'";
                }
                $appcodelist = implode(',', $appcodes);
                
                $where .= ' AND appcode in ( ' . $appcodelist . ' )';
            } elseif (is_array($where)) {
                $where['appcode'] = ['in',$request->appcodelist
                ];
            }
            $options['where'] = $where;
            
            if (! isset($data['utime'])) {
                $data['utime'] = time();
            }
            if (! isset($data['dtime']) && isset($data['datastatus'])) {
                $data['dtime'] = time();
            }
        }
        
        return [$data,$options,$host
        ];
    }
}