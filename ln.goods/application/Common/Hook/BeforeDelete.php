<?php
namespace Common\Hook;

use MrStock\System\MJC\Http\Request;
use MrStock\System\Orm\Model;

class BeforeDelete
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
        if(! isset($data['dtime'])) {
            $data['dtime'] = time();
        }
        if(! isset($data['datastatus'])) {
            $data['datastatus'] = 0;
        }
        return [$data,$options,$host
        ];
    }
}