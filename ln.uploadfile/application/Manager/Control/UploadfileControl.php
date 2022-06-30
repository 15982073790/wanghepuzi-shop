<?php
/**
 * Created by PhpStorm.
 * User: 10555
 * Date: 2019/3/14
 * Time: 17:53
 */


namespace Manager\Control;
use MrStock\System\MJC\Control;
use Common\Logic\SaveByOssLogic;
/**
 * Class UploadfileControl
 * @package Manager\Control
 * @ControlDescription(menuName="OSS上传"，cGroupName="OSS上传")
 */
class UploadfileControl extends Control
{
    /**
     * @return mixed
     * @OpDescription(whatFor="获取token",menuName="",codeMonkey="")
     */
    public function getosstokenOp(){
        $oss = new SaveByOssLogic('huihui');
        return $this->json($oss->getToken(),1);
    }


}