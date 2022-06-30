<?php
namespace MrStock\Business\ServiceSdk\Hook;

use MrStock\System\MJC\Http\Request;
use MrStock\System\Orm\Model;

class AfterMq
{

    public function run(Request $request,$msg)
    {
    	// echo 'AfterMq';  	echo "\r\n";
    	// throw new \Exception("Error Processing Request", 1);
    	
        return $msg->delivery_info['channel']->basic_ack($msg->delivery_info['delivery_tag']);
    }
}