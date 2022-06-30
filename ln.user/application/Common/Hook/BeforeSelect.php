<?php
namespace Common\Hook;

use MrStock\System\MJC\Http\Request;
use MrStock\System\Orm\Model;

class BeforeSelect
{

    /**
     * 集中 select
     * 多表 连表查询只在第一个表上加约束
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
        $where = $options['where'];
        if (is_string($where)) {
            foreach ($alias as $aliaName) {
                $where .= ' AND ' . $aliaName . '.datastatus != 0 ';

            }
        }elseif (is_array($where) || is_null($where) ) {
            foreach ($alias as $aliaName) {
                $where[$aliaName. '.datastatus'] = [
                    'neq',
                    '0'
                ];
            }
        }
        $options['where'] = $where;
        return [$data,$options,$host];
    }
}