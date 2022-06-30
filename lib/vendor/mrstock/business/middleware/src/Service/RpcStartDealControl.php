<?php
namespace MrStock\Business\Middleware\Service;

/**
 * 应用端使用的中间件-鉴权ServiceToken
 */
class RpcStartDeal
{


    public static function deal($message)
    {

        static $app;

        if (! include_once ($message["RPC_PATH"]))
            exit('rpc.php isn\'t exists!');


            $_REQUEST = $_POST = $_GET = $message;
            
       
            try {
                if (! $app) {
                    $app = new MrStock\System\MJC\App();
                }

                $response = $app->run();
                $data = $response->getContent();
            } catch (\Throwable $ex){
                (new MrStock\System\Orm\Model())->closeTransaction();
                $response= MrStock\System\Helper\Output::response($ex->getMessage(), $ex->getCode(), 200);
                $data = $response->getContent();
            }
            
            if(isset($message['callback']) && $message['callback'])
            {

            $data=str_replace($message['callback'], "", $data);
            $data=str_replace("(", "", $data);
            $data=str_replace(");", "", $data);
            }
            $result=json_decode($data);
            if(empty($result)){
                MrStock\System\MJC\Facade\Log::write(print_r($_REQUEST,true)." rs:".$data, RPCERR);
            }
            return $result;


    }
    
   
}

