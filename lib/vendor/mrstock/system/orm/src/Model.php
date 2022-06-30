<?php
namespace MrStock\System\Orm;

use MrStock\System\Orm\Connector\Mysqli as Db;
use MrStock\System\Helper\Config;
use MrStock\System\Helper\Page;

/**
 * 核心文件
 *
 * 模型类
 */
class Model
{

    protected $tablePrefix = '';

    protected $tableName = '';

    protected $options = [];

    protected $modelBb = null;

    protected $fields = [];

    protected $unoptions = true;

    protected $host;

    protected $perPage = 15;

    public $lastsql;

    public static $open_begintransaction = false;

    // sql 关键词
    protected $defaultCall = ['order', 'where', 'on', 'limit', 'having', 'group', 'lock', 'distinct', 'master', 'attr'];

    // sql 聚合函数 关键词
    protected $agCall = ['min', 'max', 'count', 'sum', 'avg'];

    public function __construct($table = null, $host = "default")
    {
        if (!is_null($table)) {
            $this->tableName = $table;
        }

        $this->host = $host;

        $conf = Config::Get('db.' . $this->host);

        if (empty($conf) || !is_array($conf) || !isset($conf['write']) || !isset($conf['tablepre'])) {
            throw new \Exception("db 配置文件错误:" . __LINE__);
        }

        $this->tablePrefix = $conf['tablepre'];

        if (!is_object($this->modelBb)) {
            $this->modelBb = new ModelDb($this->host);
        }
    }

    public function __call($method, $args)
    {
        if (in_array(strtolower($method), $this->defaultCall, true)) {

            $this->options[strtolower($method)] = $args[0];

            return $this;
        } elseif (in_array(strtolower($method), $this->agCall, true)) {

            $field = isset($args[0]) ? $args[0] : '*';
            $field = strtoupper($method) . '(' . $field . ') AS ag_' . $method;
            return $this->selectAg($field);
        } else {

            $error = 'Model Error:  Function ' . $method . ' is not exists!';
            throw new \Exception($error . ":" . __LINE__);
        }
    }

    /**
     * 第一个使用
     *
     * @param unknown $tableName
     * @return \MrStock\System\Orm\Model
     */
    public function table($tableName)
    {
        $this->options = null; // 纠正 new Model 查询公用参数
        $this->options['table'] = $tableName;

        if (strpos($tableName, ',') === false) {

            $this->tableName = $tableName;
        }

        return $this;
    }

    /**
     * 指定写库或者读库
     * @param bool $bool
     */
    public function master($useWrite = true)
    {
        $this->options['master'] = $useWrite;
        return $this;
    }

    /**
     * 查询字段
     *
     * @param unknown $field
     * @return \MrStock\System\Orm\Model
     */
    public function field($field)
    {
        $this->options['field'] = $field;
        return $this;
    }

    /**
     * 分页
     * 每页显示数 注意：如果同时使用where和page方法时，where方法要在page方法前面使用
     *
     * @param int $curPage
     *            当前页
     * @param int $eachNum
     *            每页条数
     * @return \MrStock\System\Orm\Model
     */
    public function page($curPage, $eachNum = 10)
    {
        if (!is_numeric($curPage) || intval($curPage) <= 0) {
            $curPage = 1;
        }
        if (!is_numeric($eachNum) || intval($eachNum) <= 0) {
            $eachNum = $this->perPage;
        }

        $this->options['page'] = [$curPage, $eachNum];
        //$this->unoptions = false;
        return $this;
    }

    /**
     * 查询
     *
     * @param array/int $options
     * @return null/array
     */
    public function select($options = array())
    {
        $options = $this->parseOptions($options);
        $resultSet = $this->modelBb->select($options);

        $this->lastsql = $this->modelBb->lastsql;

        return $resultSet;
    }

    /**
     * 查询聚合字段
     *
     * @param unknown $field
     * @param unknown $sepa
     * @return unknown[]|string[]|unknown|NULL
     */
    protected function selectAg($field, $sepa = null)
    {
        $this->options['field'] = $field;

        $result = $this->find();
        if (is_array($result)) {
            return reset($result);
        }
        return null;
    }

    /**
     * 多表使用别名 单表不使用别名
     * @param array $options
     * @return array
     */
    protected function parseOptions($options = array())
    {

        if (is_array($options)) $options = array_merge($this->options, $options);

        if (!isset($options['table'])) {
            $options['table'] = $this->getTableName();
        } elseif (false !== strpos(trim($options['table'], ', '), ',')) {
            foreach (explode(',', trim($options['table'], ', ')) as $val) {
                $tmp[] = $this->getTableName($val) . ' AS `' . $val . '`';
            }
            $options['table'] = implode(',', $tmp);
        } else {
            $options['table'] = $this->getTableName($options['table']);
        }
        if ($this->unoptions === true) {
            $this->options = array();
        } else {
            $this->unoptions = true;
        }
        return $options;
    }

    /**
     * 返回一条记录
     *
     * @param string/int $options
     * @return null/array
     */
    public function find()
    {
        $this->options['limit'] = 1;

        $result = $this->select();

        $this->lastsql = $this->modelBb->lastsql;

        if (is_array($result) && isset($result[0])) {
            return $result[0];
        }

        return $result;
    }

    /**
     * 删除
     *
     * @param array $options
     * @return bool/int 影响行数为0 返回true >0 则直接返回
     */
    public function delete()
    {
        $options = [];
        $options = $this->parseOptions($options);
        $result = $this->modelBb->delete($options);
        $this->lastsql = $this->modelBb->lastsql;
        if (false === $result) {
            return false;
        }
        $rows = $this->getAffectedRows();
        return $rows;
    }

    /**
     * 更新
     *
     * @param array $data
     * @param array $options
     * @return bool/int 影响行数为0 返回true >0 则直接返回
     */
    public function update($data = '', $options = array())
    {

        if (empty($data)) return false;

        // 分析表达式
        $options = $this->parseOptions($options);

        $result = $this->modelBb->update($data, $options);
        
        $this->lastsql = $this->modelBb->lastsql;
        if (false === $result) {
            return false;
        }
        $rows = $this->getAffectedRows();
        return $rows;
    }

    /**
     * 插入
     *
     * @param array $data
     * @param bool $replace
     * @param array $options
     * @return mixed int/false
     */
    public function insert($data = '', $replace = false, $options = array())
    {

        if (empty($data)) return false;
        $options = $this->parseOptions($options);
        $result = $this->modelBb->insert($data, $options, $replace);
        $this->lastsql = $this->modelBb->lastsql;
        if (false !== $result) {
            $insertId = $this->getLastId();
            if ($insertId) {
                return $insertId;
            }
        }
        return $result;
    }

    /**
     * 批量插入
     *
     * @param array $dataList
     * @param array $options
     * @param bool $replace
     * @return boolean
     */
    public function insertAll($dataList, $options = array(), $replace = false)
    {
        if (empty($dataList)) return false;

        // 分析表达式
        $options = $this->parseOptions($options);
        // 写入数据到数据库
        $result = $this->modelBb->insertAll($dataList, $options, $replace);
        if (false !== $result) return true;
        return $result;
    }

    /**
     * 直接SQL查询,返回查询结果 select 使用
     *
     * @param string $sql
     * @return array
     */
    public function query($sql, $options = [])
    {
        $options = $this->parseOptions($options);
        return $this->modelBb->query($sql, $options);
    }

    /**
     * 直接执行sql语句 insert update delete 使用
     *
     * @param unknown $sql
     * @return object
     */
    public function execute($sql)
    {
        return $this->modelBb->execute($sql);
    }

    /**
     * 开始事务 使用事务指定 host
     *
     * @param string $host
     */
    public function beginTransaction()
    {
        Db::beginTransaction($this->host);
        self::$open_begintransaction = true; //标识是否开启事务
    }

    /**
     * 提交事务
     *
     * @param string $host
     */
    public function commit()
    {
        Db::commit($this->host);
    }

    /**
     * 回滚事务
     *
     * @param string $host
     */
    public function rollback()
    {
        Db::rollback($this->host);
    }
/**
     * 开始事务 使用事务指定 host
     *
     * @param string $host
     */
    public function closeTransaction()
    {
        Db::close($this->host);
    }
    /**
     * 取得表名
     *
     * @param string $table
     * @return string
     */
    protected function getTableName($table = null)
    {
        if (is_null($table)) {
            $return = '`' . $this->tablePrefix . $this->tableName . '`';
        } else {
            $return = '`' . $this->tablePrefix . $table . '`';
        }
        return $return;
    }

    /**
     * 获取表结构
     * @param unknown $table
     * @return array|boolean[][]|unknown[][]
     */
    public function showColumns($tableFullName)
    {
        return $this->modelBb->showColumns($tableFullName);
    }

    /**
     * 取得最后插入的ID
     *
     * @return int
     */
    public function getLastId()
    {
        return $this->modelBb->getLastId();
    }

    public function getAffectedRows()
    {
        return $this->modelBb->getAffectedRows();
    }

    /**
     * 组装join
     *
     * @param string $join
     * @return Model
     */
    public function join($join)
    {
        if (false !== strpos($join, ',')) {
            foreach (explode(',', $join) as $key => $val) {
                if (in_array(strtolower($val), array('left', 'inner', 'right'))) {
                    $this->options['join'][] = strtoupper($val) . ' JOIN';
                } else {
                    $this->options['join'][] = 'LEFT JOIN';
                }
            }
        } elseif (in_array(strtolower($join), array('left', 'inner', 'right'))) {
            $this->options['join'][] = strtoupper($join) . ' JOIN';
        }
        return $this;
    }

    /**
     * 自增
     *
     * @param unknown $field
     * @param number $step
     * @return unknown
     */
    public function setInc($field, $step = 1)
    {
        return $this->setField($field, array('exp', $field . '+' . $step));
    }

    /**
     * 自减
     *
     * @param unknown $field
     * @param number $step
     * @return unknown
     */
    public function setDec($field, $step = 1)
    {
        return $this->setField($field, array('exp', $field . '-' . $step));
    }

    protected function setField($field, $value = '')
    {
        if (is_array($field)) {
            $data = $field;
        } else {
            $data[$field] = $value;
        }
        return $this->update($data);
    }
}

?>
