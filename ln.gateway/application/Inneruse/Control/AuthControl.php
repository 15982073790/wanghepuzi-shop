<?php

namespace Inneruse\Control;

use Common\Logic\AuthLogic;
use MrStock\System\Helper\Output;
use MrStock\System\MJC\Control;
use MrStock\System\MJC\Http\Request;
use MrStock\System\Orm\Model;




class AuthControl extends Control
{

    /**
     * @OpDescription(whatFor="登录鉴权",menuName="",codeMonkey="")
     */
    public function adminisauthOp(Request $request,AuthLogic $authLogic)
    {
        try {
            $authLogic->isAuth($request);
            return $this->json("ok");
        } catch (\Exception $e) {
            return $this->json($e->getMessage(), $e->getCode());
        }

    }


}