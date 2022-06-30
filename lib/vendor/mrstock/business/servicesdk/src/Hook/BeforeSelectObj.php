<?php

namespace MrStock\Business\ServiceSdk\Hook;

use MrStock\System\MJC\Http\Request;
use MrStock\System\Orm\Model;

class BeforeSelectObj
{

    /**
     * 集中 select
     * 服务语句中加入 obj 字段 查询条件
     *
     * @param Request $request
     * @param unknown $options
     * @return string
     */
    public function run(Request $request, $params)
    {
        if ($request->ignoreBeforeSelect) {
            return;
        }

        list ($data, $options, $host) = $params;
        $tmpTables = $options['table'];
        $tables = explode(',', $tmpTables);

        $join = $options['join'];
        //有连表查询
        if (is_array($join) && count($join) > 0) {
            $tables = [array_shift($tables)];
        }

        $tableNames = [];
        $alias = [];
        foreach ($tables as $table) {
            $aliaName = $tableName = $table;
            if (stripos($table, ' as ') > 0) {
                $table = preg_split('/ (a|A)(s|S) /', $table);
                $tableName = trim(array_shift($table));
                $aliaName = trim(end($table));
            }
            $tableNames[] = $tableName;
            $alias[] = $aliaName;
        }

        $model = new Model(null, $host);
        foreach ($tableNames as $key => $table) {
            $table = $model->showColumns($table);
            $fields = array_keys($table);

            if (!in_array('obj', $fields)) {
                unset($tableNames[$key]);
                unset($alias[$key]);
            }
        }

        $where = $options['where'];
        //key value模式
        $objWhere = $request->objwhere;
        $objMap = \json_decode($objWhere, true);
        if ($objMap) {
            if (is_string($where) || empty($where)) {
                if (empty($where)) {
                    $where = ' 1=1 ';
                }

                foreach ($alias as $aliaName) {
                    foreach ($objMap as $key => $value) {
                        $where .= ' AND ' . $aliaName . '.' . $key . ' = ' . $value;
                    }
                }
            } elseif (is_array($where)) {
                foreach ($alias as $aliaName) {
                    foreach ($objMap as $key => $value) {
                        $where[$aliaName . '.' . $key] = ['eq', $value];
                    }
                }
            }
            $options['where'] = $where;
        }

        return [$data, $options, $host];
    }
}