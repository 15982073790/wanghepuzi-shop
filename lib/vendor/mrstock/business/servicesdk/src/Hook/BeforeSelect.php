<?php
namespace MrStock\Business\ServiceSdk\Hook;

use MrStock\System\MJC\Http\Request;
use MrStock\System\Orm\Model;

class BeforeSelect
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

            if(!in_array('appcode', $fields)){
                unset($tableNames[$key]);
                unset($alias[$key]);
            }
        }
         //本地兼容
        if(!$request->appcodelist){
            $request->appcodelist = $request->appcode;
        }
        $where = $options['where'];
        if (is_string($where)|| empty($where)) {
            if (empty($where)) {
                $where = ' 1=1 ';
            }
            $appcodes = explode(',', $request->appcodelist);
            foreach ($appcodes as $k=>$v){
                $appcodes[$k] = "'".$v."'";
            }
            $appcodelist = implode(',', $appcodes);
            
            foreach ($alias as $aliaName) {
                $where .= ' AND ' . $aliaName. '.appcode in ( ' . $appcodelist. ' ) ';
            }
        } elseif (is_array($where)) {
            foreach ($alias as $aliaName) {
                $where[$aliaName. '.appcode'] = [
                    'in',
                    $request->appcodelist
                ];
            }
        }
        $options['where'] = $where;
        return [$data,$options,$host];
    }
}