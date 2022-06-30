<?php
namespace MrStock\System\Orm;
use MrStock\System\Helper\Config;
class sqlsrv{
   
    private  $error_log = array();
    private  $sql_log = array();
    private  $query_id;
    private  $num_rows;
    private  $conn;

    //connection
    private  function connect() {
        //$sqlsrv_config = C('sqlsrv');
        $sqlsrv_config = Config::Get('sqlsrv');
        $this->conn = @sqlsrv_connect($sqlsrv_config['host'], array('UID' => $sqlsrv_config['uid'], 'PWD'=> $sqlsrv_config['pwd'], 'Database' => $sqlsrv_config['db']));
        if($this->conn === false) {
            $this->error_log[] = sqlsrv_errors();
            die();
        }
    }
  
    //query source
    private  function query($sql){
        $stmt = sqlsrv_query($this->conn, $sql);
        $this->sql_log[] = $sql;
        if($stmt === false) {
            $this->error_log[] = sqlsrv_errors();
        } else {
            $this->query_id = $stmt;
            $this->num_rows = $this->affectedRows();
        }
    }
  
    //fetch data
    public  function fetch_all($sql) {
        $this->connect();
        $this->query($sql);
        $data = array();
        while($row = @sqlsrv_fetch_array($this->query_id, SQLSRV_FETCH_ASSOC)) {
            $data[] = $row;
        }
        return $data;
    }
    // $DB->count(select   *   from  users)
    public  function fetch_one($sql){
        $this->connect();
        $this->query($sql);
        return  sqlsrv_fetch_array($this->query_id, SQLSRV_FETCH_ASSOC);
  
    }
    // $DB->count(select   count(*)   from  users)
    public  function count($sql){
  
        $count=$this->fetch_one($sql);
        return $count[""];
  
    }
  
    public  function affectedRows() {
        return ($this->query_id) ? @sqlsrv_num_rows($this->query_id) : false;
    }
}
  
?>