<?php
namespace MrStock\Business\ServiceSdk\Hook;

use MrStock\System\MJC\Http\Request;
use MrStock\System\Orm\Model;
use MrStock\System\Helper\Cache\File;

class BeforeSelectInstitution
{

    /**
     * 集中 select
     * 服务语句中加入 appcode 条防止与删除,多表 连表查询只在第一个表上加约束
     *
     * @param Request $request            
     * @param unknown $options            
     * @return string
     */
    public function run(Request $request, $params)
    {
        if($request->ignoreBeforeSelect){
            return ;
        }
        
        list ($data, $options,$host) = $params;
        $tmpTables = $options['table'];
        $tables = explode(',', $tmpTables);
        
        $join = $options['join'];
        //有连表查询
        if(is_array($join) && count($join)>0){
            $tables = [array_shift($tables)];
        }
        
        $tableNames = [];
        $alias = [];
        foreach ($tables as $table) {
            $aliaName = $tableName = $table;
            if (stripos($table,' as ') > 0) {
                $table = preg_split('/ (a|A)(s|S) /',$table);
                $tableName = trim(array_shift($table));
                $aliaName= trim(end($table));
            }
            $tableNames[] = $tableName;
            $alias[] = $aliaName;
        }

        $model = new Model(null,$host);
        foreach ($tableNames as $key=> $table){
            $table = $model->showColumns($table);
            $fields = array_keys($table);

            if(!in_array('institution_id', $fields)){
                unset($tableNames[$key]);
                unset($alias[$key]);
            }
        }

        $where = $options['where'];
        $institution_id = $request->institution_id;
        if (is_string($where)|| empty($where)) {
            if (empty($where)) {
                $where = ' 1=1 ';
            }
            foreach ($alias as $aliaName) {
                $where .= ' AND ' . $aliaName. '.institution_id = '.$institution_id;
            }
        } elseif (is_array($where)) {
            foreach ($alias as $aliaName) {
                $where[$aliaName. '.institution_id'] = $institution_id;
            }
        }
        $options['where'] = $where;

        return [$data,$options,$host];
    }
}