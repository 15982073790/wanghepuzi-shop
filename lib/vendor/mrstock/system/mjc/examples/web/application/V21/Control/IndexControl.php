<?php
namespace V21\Control;

use MrStock\System\MJC\Control;
use MrStock\System\Orm\Model;
use MrStock\Business\Base\Logic\ChangeTelLogic;
use MrStock\System\Redis\RedisHelper;
use MrStock\System\Queue\MQ;
use MrStock\System\Queue\QueueServer;
use MrStock\Business\ServiceSdk\ServerStock\ServerStockRpc;
use MrStock\System\MJC\Container;
use MrStock\System\MJC\Facade\Log;
use MrStock\System\MJC\Log\LogLevel;

use App\Facade\HqRedisFacade;
use App\Facade\RedisFacade;
use App\Facade\DefaultMQ;

use App\Model\MemberModel;

class IndexControl extends Control
{
    public $middleware = ['V21\Middleware\Auth'];
    
    public function testOp()
    {
        //new aaa();
        //return $this->json([1]);
/*

        Log::write('111',LogLevel::ACCESS);
        Log::write('111',LogLevel::ACCESS);
        Log::write('111',LogLevel::ACCESS);

        return $this->json("true");
        $memberModel = new MemberModel();
    
        
        /*
        var_dump(HqRedisFacade::del("test1"));
        var_dump(HqRedisFacade::set("test1",222));
        var_dump(HqRedisFacade::get("test1"));
        
        var_dump(RedisFacade::del("test2"));
        var_dump(RedisFacade::set("test2",222));
        var_dump(RedisFacade::get("test2"));
  
        //var_dump(Container::getInstance());
        exit();
       Redis::set('k1',4444);
       $redis = new RedisHelper('queue',7);
       $re = $redis->host('queue1',2)->set("k1",111);
       var_dump($re);
       $re = $redis->get("k1");
       var_dump($re);
       
       exit();
        
        $data['stage'] = "sendsms";
        $data['member_id'] = "10";
        $data['client_id'] = "20";
        $data['message'] = "message";
        var_dump(DefaultMQ::push($data));
        
        
        var_dump(HqRedis::set("test1",111));
        var_dump(HqRedis::get("test1"));exit();
        
        $re = ServerStockRpc::get("5b6ab2f42cc71c9mqblxxgj1R2VmMUswV0NGOFhjZlZPM0E5MDV1QT09","sh600760");
        return $this->json($re);
        exit();
        
        $mq = new MQ();
        $data['stage'] = "sendsms";
        $data['member_id'] = "10";
        $data['client_id'] = "20";
        $data['message'] = "message";
        var_dump($mq->push($data));
        
        $queueName= "sendsms";
        $worker = new QueueServer($queueName);
        $list_key = $worker->scan();
        var_dump($list_key);
        $key = $list_key[0];
        var_dump($key);
        $data = $worker->pop($key);
        var_dump($data);
        exit();
        $redis = new RedisHelper();
        
        var_dump($redis->set("aaaa",111));
        var_dump($redis->get("aaaa"));
        
        var_dump($redis->host("queue",5)->set("aaaa",111));
        var_dump($redis->host("queue",5)->get("aaaa"));
        
        exit(); 
        */
        //return $this->json("true");
        echo changetelLogic::changemobile('17780510690');echo "<br/>";
        echo changetelLogic::changemobile('483643546956705178704d37704e4f65676a623177773d3d',1);
exit();
        /*
        $m = [13730856862];
        $a = \MrStock\Business\ServiceSdk\secretmobile\SecretMobileRpc::encrypt("5b6ab2f42cc71c9mqblxxgj1R2VmMUswV0NGOFhjZlZPM0E5MDV1QT09", $m);
        var_dump($a);
        
        echo array_values($a)[0];

        $a = \MrStock\Business\ServiceSdk\secretmobile\SecretMobileRpc::decrypt("5b6ab2f42cc71c9mqblxxgj1R2VmMUswV0NGOFhjZlZPM0E5MDV1QT09", [array_values($a)[0]]);
        var_dump($a);
*/
       // Log::write('111',LogLevel::ACCESS);
        //Log::write('111',LogLevel::REDISRECORD);

        echo 222;
        $a=new \MrStock\Business\Auth\Service\AuthenticateRpcControl();
        
        echo 333;
        $_REQUEST['servicestoken'] = '5b6ab2f42cc71c9mqblxxgj1R2VmMUswV0NGOFhjZlZPM0E5MDV1QT09';
        $this->app->request->param=$_REQUEST;
        $this->app->request->server=$_SERVER;
        $rr=$a->run();
        var_dump($rr->request->param);
        return ;
        exit();
        var_dump(Redis::set('aaaa',1111));
        var_dump(Redis::get('aaaa'));
    

        $this->app->log->write('111');
        $model = new Model("member");

        
        //Select 方法：取得查询信息，返回结果为数组，如果未找到数据返回null，select一般在where,order,tabale等方法的后面，作为最后一个方法来使用。如:
        // 查询会员表全部信息
        $re = $model->page(1,10)->select();
        print_r($re);
        echo "\n";
        
        //取得性别为1的会员列表信息, 注意：select方法需要在连贯操作中的最后一步出现
        $re = $model->where(array('member_sex'=>1))->page(1,10)->select();
        print_r($re);
        echo "\n";
        
        
        
        return $this->json("true");
    }  
}