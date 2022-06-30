<?php

namespace MrStock\System\Orm\Connector;

use MrStock\System\MJC\Facade\Log;
use MrStock\System\MJC\Log\LogLevel;
use MrStock\System\MJC\Container;
use MrStock\System\Helper\Config;
use MrStock\System\MJC\Facade\Hook;
use MrStock\System\MJC\Facade\Debug;
/**
 * mysqli驱动
 *
 *
 * @package
 *
 */
class Mysqli
{

    //
    protected static $linkPool = [];

    protected function __construct()
    {
        if (!extension_loaded('mysqli')) {
            throw new \Exception("Db Error: mysqli is not install");
        }
    }

    protected static function connect($host = 'default', $useRead = true)
    {

        $conf = Config::Get('db.' . $host);

        if (empty($conf) || !is_array($conf) || !isset($conf['write'])) {
            throw new \Exception("db 配置文件错误");
        }

        if (!isset($conf['read'])) {
            $conf['read'] = $conf['write'];
        }

        if (!isset($conf['charset']) || empty($conf['charset'])) {
            $conf['charset'] = 'UTF-8';
        }

        $linkName = self::getLinkName($host, $useRead);

        // 事务不检查 连接持续时间
        if (isset(self::$linkPool[$linkName]['handle']) && is_object(self::$linkPool[$linkName]['handle'])) { // 如果已经连接
            if (!self::$linkPool[$linkName]['iftransacte']) { // 当前连接启用了事务 则不检查持续时间 也不重新建立连接
                return;
            }
        }

        // 连接创建时间
        $createTime = 0;
        if (isset(self::$linkPool[$linkName]['createtime'])) {
            $createTime = intval(self::$linkPool[$linkName]['createtime']);
        }

        // 如果大于链接 默认持续时间 则重新建立链接
        if ((time() - $createTime) < 30) {
            // 检查链接
            if (is_object(self::$linkPool[$linkName]['handle'])) {

                if (!self::$linkPool[$linkName]['handle']->ping()) {
                    self::$linkPool[$linkName]['handle']->close();
                    unset(self::$linkPool[$linkName]);
                } else {
                    return;
                }
            }
        }

        if (isset(self::$linkPool[$linkName]['handle']) && !empty(self::$linkPool[$linkName]['handle'])) {
            @mysqli_close(self::$linkPool[$linkName]['handle']);
        }
        self::$linkPool[$linkName]['createtime'] = time();

        list($server, $port) = self::parseServerPort($conf, $useRead);

        self::$linkPool[$linkName]['handle'] = new \mysqli('p:' . $server, $conf['user'], $conf['pwd'], $conf['name'], $port);

        if (mysqli_connect_errno()) {
            $error = self::$linkPool[$linkName]['sqling'] . '---' . $linkName . " Db Error: database connect failed";
            Log::write($error, LogLevel::DBERR);
            throw new \Exception("Db Error: database connect failed");
        }

        switch (strtoupper($conf['charset'])) {
            case 'UTF-8':
                $query_string = "
		                 SET CHARACTER_SET_CLIENT = utf8mb4,
		                 CHARACTER_SET_CONNECTION = utf8mb4,
		                 CHARACTER_SET_DATABASE = utf8mb4,
		                 CHARACTER_SET_RESULTS = utf8mb4,
		                 CHARACTER_SET_SERVER = utf8mb4,
		                 COLLATION_CONNECTION = utf8mb4_general_ci,
		                 COLLATION_DATABASE = utf8mb4_general_ci,
		                 COLLATION_SERVER = utf8mb4_general_ci,
		                 sql_mode=''";
                break;
            case 'GBK':
                $query_string = "
		   			    SET CHARACTER_SET_CLIENT = gbk,
		                 CHARACTER_SET_CONNECTION = gbk,
		                 CHARACTER_SET_DATABASE = gbk,
		                 CHARACTER_SET_RESULTS = gbk,
		                 CHARACTER_SET_SERVER = gbk,
		                 COLLATION_CONNECTION = gbk_chinese_ci,
		                 COLLATION_DATABASE = gbk_chinese_ci,
		                 COLLATION_SERVER = gbk_chinese_ci,
		                 sql_mode=''";
                break;
            default:
                $error = self::$linkPool[$linkName]['sqling'] . '---' . $linkName . " Db Error: charset is Invalid";
                Log::write($error, LogLevel::DBERR);
                throw new \Exception($error);
        }

        // 进行编码声明
        if (!self::$linkPool[$linkName]['handle']->query($query_string)) {
            $error = self::$linkPool[$linkName]['sqling'] . '---' . $linkName . " Db Error: " . mysqli_error(self::$linkPool[$linkName]['handle']);
            Log::write($error, LogLevel::DBERR);
            throw new \Exception("Db Error: " . mysqli_error(self::$linkPool[$linkName]['handle']));
        }

        self::$linkPool[$linkName]['iftransacte'] = true;
    }

    protected static function parseServerPort($conf, $useRead)
    {
        $hostHandle = self::getHostHandle($useRead);

        $server = $conf[$hostHandle]['host'];
        $port = $conf[$hostHandle]['port'];
        return [$server, $port];
    }

    protected static function getMillisecond()
    {
        return microtime(true);
    }

    protected static function getHostHandle($useRead)
    {
        if ($useRead) {
            $hostHandle = 'read';
        } else {
            $hostHandle = 'write';
        }
        return $hostHandle;
    }

    protected static function getLinkName($host, $useRead)
    {

        $hostHandle = self::getHostHandle($useRead);

        $linkName = $host . '_' . $hostHandle;
        return $linkName;
    }

    /**
     * 执行查询
     *
     * @param string $sql
     * @param string $host
     * @param bool $specifyMaster
     *            指定使用主库
     * @return object
     */
    protected static function query($sql, $host = 'default', $useRead = true)
    {
        $startTime = self::getMillisecond();
        self::connect($host, $useRead);
        $linkName = self::getLinkName($host, $useRead);
        self::$linkPool[$linkName]['sqling'] = $sql;

        $query = self::$linkPool[$linkName]['handle']->query($sql);
//         echo mysqli_error(self::$linkPool[$linkName]['handle']);


        $debug_state=true;
        $debug_result=null;

        if ($query === false) {
            $error = self::$linkPool[$linkName]['sqling'] . '---' . $linkName . ' Db Error: ' . mysqli_error(self::$linkPool[$linkName]['handle']);
            Log::write($error, LogLevel::DBERR);

            $debug_state=false;
            $debug_result=__LINE__;

            throw new \Exception($error . '<br/>' . $sql);
        }

        $endTime = self::getMillisecond();
        $runTime = round($endTime - $startTime, 6) * 1000;
        if ($runTime > 1000) {
            Log::write(self::$linkPool[$linkName]['sqling'] . '---' . $linkName . " [ RunTime:" . $runTime . " 毫秒 ]", LogLevel::DBSLOW);
        }

        $Log_query_array = array();
      


        Log::write(self::$linkPool[$linkName]['sqling'] . '---' . $linkName .var_export($Log_query_array, true).'---'. " [ RunTime:" . $runTime . " 毫秒 ]", LogLevel::SQLRECORD);

        Hook::listen('debug_record', ['type'=>'Mysqli','link'=>$linkName,'command'=>$sql,'starttime'=>$startTime,'result'=>$debug_result,'state'=>$debug_state]);


        return $query;
    }


    /**
     * 取得数组
     *
     * @param string $sql
     * @return bool/null/array
     */
    public static function getAll($sql, $host = 'default', $useRead = true)
    {
        $result = self::query($sql, $host, $useRead);
        if ($result === false) {
            return $result;
        }
        $array = array();
        $tmp = mysqli_fetch_array($result, MYSQLI_ASSOC);
        while ($tmp) {
            $array[] = $tmp;
            $tmp = mysqli_fetch_array($result, MYSQLI_ASSOC);
        }
         if(Debug::isApi()) {
            Log::write("sql---".$sql."---result---".print_r($result, true)."---getAll---".var_export($array, true), LogLevel::SQLRECORD);
            
         }
        return !empty($array) ? $array : array();
    }


    /**
     * 取得上一步插入产生的ID
     *
     * @return int
     */
    public static function getLastId($host = 'default')
    {
        $linkName = self::getLinkName($host, false);
        $id = mysqli_insert_id(self::$linkPool[$linkName]['handle']);
        if (!$id) {
            $result = self::query('SELECT last_insert_id() as id', $host, false);
            if ($result === false) return false;
            $id = mysqli_fetch_array($result, MYSQLI_ASSOC);
            $id = $id['id'];
        }
        return $id;
    }

    public static function getAffectedRows($host = 'default')
    {
        $linkName = self::getLinkName($host, false);
        $rows = mysqli_affected_rows(self::$linkPool[$linkName]['handle']);

        return $rows;
    }


    /**
     * 执行SQL语句
     *
     * @param string $sql
     *            待执行的SQL
     * @return
     *
     */
    public static function execute($sql, $host = 'default', $useRead = false)
    {
        $result = self::query($sql, $host, $useRead);

        return $result;
    }

    /**
     * 格式化字段
     *
     * @param string $key
     *            字段名
     * @return string
     */
    protected static function parseKey(&$key)
    {
        $key = trim($key);
        if (!preg_match('/[,\'\"\*\(\)`.\s]/', $key)) {
            $key = '`' . $key . '`';
        }
        return $key;
    }

    /**
     * 格式化值
     *
     * @param mixed $value
     * @return mixed
     */
    protected static function parseValue($value)
    {
        $value = addslashes(stripslashes($value)); // 重新加斜线，防止从数据库直接读取出错
        return "'" . $value . "'";
    }

    public static function showColumns($table, $host = 'default')
    {
        $sql = 'SHOW COLUMNS FROM ' . $table;
        $result = self::query($sql, $host, false);

        if ($result === false) return array();
        $array = array();
        $tmp = mysqli_fetch_array($result, MYSQLI_ASSOC);
        while ($tmp) {
            $array[$tmp['Field']] = array('name' => $tmp['Field'], 'type' => $tmp['Type'], 'null' => $tmp['Null'], 'default' => $tmp['Default'], 'primary' => (strtolower($tmp['Key']) == 'pri'), 'autoinc' => (strtolower($tmp['Extra']) == 'auto_increment'));
            $tmp = mysqli_fetch_array($result, MYSQLI_ASSOC);
        }
        return $array;
    }
    public static function close($host = 'default')
    {
        $useRead = false;
        $linkName = self::getLinkName($host, $useRead);
        if (is_object(self::$linkPool[$linkName]['handle'])) { // 如果已经连接
            self::$linkPool[$linkName]['handle']->close();
            unset(self::$linkPool[$linkName]);
        }
    }
    /**
     * 开启事务 新起连接
     *
     * @param string $host
     */
    public static function beginTransaction($host = 'default')
    {
        $useRead = false;
        $linkName = self::getLinkName($host, $useRead);
        // 如果开启事务 则新起连接
        if (is_object(self::$linkPool[$linkName]['handle'])) { // 如果已经连接
            self::$linkPool[$linkName]['handle']->close();
            unset(self::$linkPool[$linkName]);
        }

        self::connect($host, $useRead);
        if (self::$linkPool[$linkName]['iftransacte']) {
            self::$linkPool[$linkName]['handle']->autocommit(false); // 关闭自动提交
        }
        self::$linkPool[$linkName]['iftransacte'] = false;
    }

    public static function commit($host = 'default')
    {
        $useRead = false;
        $linkName = self::getLinkName($host, $useRead);
        if (!self::$linkPool[$linkName]['iftransacte']) {
            $result = self::$linkPool[$linkName]['handle']->commit();
            self::$linkPool[$linkName]['handle']->autocommit(true); // 开启自动提交
            self::$linkPool[$linkName]['iftransacte'] = true;
            if (!$result) throw new \Exception("Db Error: " . mysqli_error(self::$linkPool[$linkName]['handle']));
        }
    }

    public static function rollback($host = 'default')
    {
        $useRead = false;
        $linkName = self::getLinkName($host, $useRead);
        if (!self::$linkPool[$linkName]['iftransacte']) {
            $result = self::$linkPool[$linkName]['handle']->rollback();
            self::$linkPool[$linkName]['handle']->autocommit(true);
            self::$linkPool[$linkName]['iftransacte'] = true;

            $error = self::$linkPool[$linkName]['sqling'] . '---' . $linkName . ' Db Rollback';
            Log::write($error, LogLevel::DBROLLBACK);
            if (!$result) {
                $error = self::$linkPool[$linkName]['sqling'] . '---' . $linkName . ' Db Error rollback: ' . mysqli_error(self::$linkPool[$linkName]['handle']);
                Log::write($error, LogLevel::DBERR);
                throw new \Exception("Db Error: " . mysqli_error(self::$linkPool[$linkName]['handle']));
            }
        }
    }
}