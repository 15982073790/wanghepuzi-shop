<?php
/*要求每句话之间没有关联，不能保证顺序执行*/
namespace MrStock\Business\ServiceSdk\PhpAmqpLibSdk;

use MrStock\System\MJC\Container;
use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Message\AMQPMessage;
use MrStock\System\Helper\Config;
use MrStock\System\Helper\Tool;
use MrStock\System\MJC\Facade\Hook;
use MrStock\System\MJC\Facade\Log;
use MrStock\System\MJC\Log\LogLevel;

class PhpAmqpLibFactory
{
     static private $connection;
     static private $channel;



    protected static function connect($host)
    {
        if (!self::$connection[$host]['handle']||!self::$channel[$host]) {
            $rabbitmq_config = Config::get("rabbitmq.default");

            self::$connection[$host]['handle'] = new AMQPStreamConnection($rabbitmq_config["server"], $rabbitmq_config["port"], $rabbitmq_config["username"], $rabbitmq_config["password"]);

            self::$connection[$host]['createtime'] = time();
            self::$channel[$host] = self::$connection[$host]['handle']->channel();

        }
        if ((time() - self::$connection[$host]['createtime']) > 30) {
            Log::write(' say reconnect_begin');
            self::$channel[$host]->close();
            self::$connection[$host]['handle']->close();
            self::$connection=[];
            self::$channel[$host]=[];
            self::connect($host);
            Log::write(' say reconnect_end');
        }
    }

    public static function say($event_name,$say_words)
    {
         try{

                if(empty($event_name)||empty($say_words)){
                    throw new \Exception("say param error",-1);
                }

                if(!empty($say_words["mq_msg_id"])||!empty($say_words["mq_msg_time"])){
                    throw new \Exception("mq_msg_id or mq_msg_time param do not your ini",-1);
                }
                if(!is_array($say_words)) throw new \Exception("say_words param error",-1);
                if(is_array($say_words)) $say_words=Tool::arrToStr($say_words);
                $microtime=microtime();
                $mq_msg_id=md5($event_name.$say_words.$microtime);
                $say_words=Tool::strToArr($say_words);
                $say_words["mq_msg_id"]=$mq_msg_id;
                $say_words["mq_msg_time"]=$microtime;

                if(is_array($say_words)) $say_words=Tool::arrToStr($say_words);


                Log::write(" say_begin :".$event_name.':'.print_r($say_words,true));

                self::connect("say");

                $requests = Container::get("request");
                $site=$requests["site"];

                self::$channel["say"]->exchange_declare($site, 'direct', false, true, false);

                $severity =$site.'.'.$event_name;

                $msg1 = new AMQPMessage($say_words,array('delivery_mode' => 2));

                self::$channel["say"]->basic_publish($msg1, $site, $severity);
            }catch (\Exception $e) {
                Log::write($e->getCode().' say:'.$e->getMessage()." say_err :".$event_name.':'.print_r($say_words,true), LogLevel::MQERR);
                throw new \Exception($e->getMessage(),-1);
            }



        Log::write(" say_end : ".$severity.':'.print_r($say_words,true));
        return true;

    }

    public static function listen($fromsite,$event_name,$hook_class)
    {
        $host="listen";
        if(empty($fromsite)||empty($event_name)||empty($hook_class)){
            throw new \Exception("listen param error",-1);
        }
        self::connect($host);

        self::$channel[$host]->exchange_declare($fromsite, 'direct', false, true, false);

        $requests = Container::get("request");
        $site=$requests["site"];


        list($queue_name, ,) = self::$channel[$host]->queue_declare($site.".".$fromsite.".".$event_name, false, true, false, false);

        $severities=[$fromsite.".".$event_name];

        if(empty($severities )) {
            throw new \Exception("Usage: $argv[0] [info] [warning] [error]",-1);
            echo "Usage: $argv[0] [info] [warning] [error]";
            exit(1);
        }

        foreach($severities as $severity) {
            self::$channel[$host]->queue_bind($queue_name, $fromsite, $severity);
        }

        echo ' [*] Waiting for logs. To exit press CTRL+C', "\n";

        $callback = function($msg) use($hook_class){

           $hook_class[]="MrStock\Business\ServiceSdk\Hook\AfterMq";

            try{
                $requests = Container::get("request");
                $requests["mqinfo"]=$msg->body;
                $msgdata = json_decode($msg->body, true);
                $deal_err_hoook="";

                foreach ($hook_class as $key => $value) {

                    $deal_err_hoook=$key;

                    $err_hook_num=self::get_err_hook($msg->body);
                    echo "\n";
                    echo $msgdata["mq_msg_id"]."_".date("Y/m/d H:i:s")."_".$key."_".$err_hook_num;
                    if($key<$err_hook_num){
                        continue;
                    }
                    echo "do";
                    if(strpos($value,'AfterMq') !== false){
                        Hook::exec($value,$msg);
                    }else{

                        Hook::exec($value,$msg->body);
                    }
                    echo "end"."\n";

                }

                //删除控制文件
                //if($err_hook_num!==false){

                    self::delete_err_hook($msg->body);
                //}

                $deal_err_hoook="";
            }catch (\Throwable $e) {

                self::listen_exception($e,$deal_err_hoook,$msg->body);
                if(strpos($e->getMessage(),'RPCERROR') !== false){
                    echo 'RPCERROR shutdowm';
                    exit;
                }

                //echo "err\n";exit;
            }

        };

        self::$channel[$host]->basic_consume($queue_name, '', false, false, false, false, $callback);
      
        while(count(self::$channel[$host]->callbacks)) {

            //echo 1;echo "\n";
            self::$channel[$host]->wait();
           //echo 2;echo "\n";
        }

        self::$channel[$host]->close();
        self::$connection["listen"]['handle']->close();
    }
    protected static function listen_exception($e,$deal_err_hoook,$msg)
    {
        self::write_err_hook($deal_err_hoook,$msg);

        (new \MrStock\System\Orm\Model())->closeTransaction();

        echo $e->getMessage();echo "\r\n";

        $error=[];
        $error['code']=$e->getcode();
        $error['message'] = $e->getMessage();
        $error['file'] = $e->getFile();
        $error['line'] = $e->getLine();
        $error['msg'] = $msg;
        Log::write(print_r($error, true), LogLevel::MQERR);

    }
    protected static function write_err_hook($deal_err_hoook,$msg)
    {
        $msg_arr=json_decode($msg,true);
        return \MrStock\System\Helper\Cache\File::set("mqdealhook_".$msg_arr["mq_msg_id"], $deal_err_hoook, 0);

    }
    protected static function get_err_hook($msg)
    {
        $msg_arr=json_decode($msg,true);
        $err_hook_num=\MrStock\System\Helper\Cache\File::get("mqdealhook_".$msg_arr["mq_msg_id"]);

        return $err_hook_num;
    }
    protected static function delete_err_hook($msg)
    {
        $msg_arr=json_decode($msg,true);

        return \MrStock\System\Helper\Cache\File::delete("mqdealhook_".$msg_arr["mq_msg_id"]);

    }
}
