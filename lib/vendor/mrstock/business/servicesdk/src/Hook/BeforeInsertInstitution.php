<?php
namespace MrStock\Business\ServiceSdk\Hook;

use MrStock\System\MJC\Http\Request;
use MrStock\System\Orm\Model;

class BeforeInsertInstitution
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

        if (in_array('institution_id', $fields) && ! isset($data['institution_id'])){
            $data['institution_id'] = $request->institution_id;
        }

        return [$data,$options,$host];
    }
}