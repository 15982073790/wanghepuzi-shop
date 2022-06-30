<?php
namespace MrStock\Business\ServiceSdk\Hook;

use MrStock\System\MJC\Http\Request;
use MrStock\System\Orm\Model;
use MrStock\System\MJC\Facade\Debug;

class DebugRecord
{

    public function run(Request $request,$params)
    {
        if(isset($request["isdebug"])&&!empty($request["isdebug"])){
            $debugBody = [];
            $debugBody['type'] = $params['type'];
            $debugBody['link'] =  $params['link'];
            $debugBody['command'] =  $params['command'];
            $debugBody['state'] = $params['state'];
            $debugBody['runtime'] = round((microtime(true) - $params['starttime']) * 1000, 3);
            $debugBody['result'] = isset($params['result'])?$params['result']:null;

            $step = Debug::getStep();
            Debug::data(Debug, $debugBody);
            if (isset($params['result_debug'])) {
                Debug::data($step . ':child', $params['result_debug']);
                unset($params['result_debug']);
            }
                
        }
    	
        
    }
}