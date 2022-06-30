<?php
namespace Common\Hook;

use MrStock\System\MJC\Http\Request;
class BeforeInsert
{

    /**
     * 集中处理 insert insertall 公共字段
     * @param Request $request            
     * @param unknown $data            
     * @return unknown
     */
    public function run(Request $request, $params)
    {
        if ($request->ignoreBeforeInsert) {
            return;
        }
        list ($data, $options, $host) = $params;
        if (! isset($data['itime'])) {
            $data['itime'] = time();
        }
        if (! isset($data['datastatus'])) {
            $data['datastatus'] = 1;
        }
        return [$data,$options,$host
        ];
    }
}