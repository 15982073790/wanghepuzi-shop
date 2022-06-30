<?php
namespace MrStock\Business\ServiceSdk\Hook;

use MrStock\System\MJC\Http\Request;
use MrStock\System\Orm\Model;

class BeforeUpdateDeleteInstitution
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
        $institution_id = $request->institution_id;
        if (in_array('institution_id', $fields)) {
            $where = $options['where'];
            if (is_string($where)) {
                if (empty($where)) {
                    $where = ' WHERE 1=1 ';
                }
                $where .= ' AND institution_id = '.$institution_id;
            } elseif (is_array($where)) {
                $where['institution_id'] = $institution_id;
            }
            $options['where'] = $where;
        }
        
        return [$data,$options,$host];
    }
}