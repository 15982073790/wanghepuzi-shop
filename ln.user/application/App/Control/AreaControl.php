<?php

namespace App\Control;

use MrStock\System\MJC\Control;
use MrStock\System\MJC\Http\Request;
use Common\Model\Area;
use MrStock\System\Helper\Config;
use Common\Libraries\AppTools;

/**
 * @OpDescription(whatFor="区域",codeMonkey="")
 */
class AreaControl extends Control
{
    public $middleware = [
        'control' => []
    ];

    /**
     * @OpDescription(whatFor="区域列表",codeMonkey="")
     */
    public function arealistOp(Request $request, Area $new_area)
    {
        $area_id     = $request['area_id'];
        $res['list'] = $new_area->selectByCondition(['pid' => $area_id], 'area_id,area_name,pid');
        return $this->json($res);
    }
    /**
     * @OpDescription(whatFor="所有区域列表",codeMonkey="")
     */
    public function arealevellistOp(Request $request, Area $new_area)
    {
        $res['list'] = $new_area->selectByCondition([],'area_id,area_name,pid,level');
        return $this->json($res);
    }


}
