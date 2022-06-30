<?php
namespace Common\Hook;

use MrStock\System\MJC\Http\Request;
use MrStock\System\Orm\Model;

class BeforeUpdate
{

    /**
     * @param Request $request            
     * @param unknown $options            
     * @return string
     */
    public function run(Request $request, $params)
    {
        if ($request->ignoreBeforeUpdateDelete) {
            return;
        }
        list ($data, $options, $host) = $params;

        if($data['datastatus'] === 0 || $data['datastatus'] === '0'){
            $data['dtime'] = time();
        }else{
            $data['utime'] = time();
        }
        
        return [$data,$options,$host
        ];
    }
}