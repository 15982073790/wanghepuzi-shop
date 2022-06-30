<?php
namespace Init\Control;

use MrStock\System\Helper\Config;
use MrStock\System\MJC\Control;
use MrStock\System\Orm\Model;
use MrStock\System\Helper\Arr;
use MrStock\System\Orm\Connector\Mysqli;

/**
 * @ControlDescription(menuName="控制器菜单描述")
 **/
class ApiControl extends Control
{
    private $serviceSite;
    private $apiModel;
    private $apiMenuModel;
    private $apiOtherModel;
    private $insertNum = 0;
    private $insertErrorNum = 0;
    private $updateNum = 0;
    private $updateErrorNum = 0;

    public function __construct()
    {
        $this->serviceSite = Config::get('serviceSite');
        $this->apiModel = new Model('api');
        $this->apiMenuModel = new Model('api_menu');
        $this->apiOtherModel = new Model('api_other');
    }

    /**
     * 插入api
     */
    public function addOp()
    {

        $paths = $this->getPath();
//        $paths = ["F:/project/caixuetang/cxt.gateway/"];
        foreach ($paths as $path) {

            //记录数据
            $data = [];
            $siteMatches = explode('.',$path);
            $site = strtolower(trim($siteMatches[1],'/'));
            //循环控制器
            foreach (glob($path .'/application/Manager/Control/*Control.php') as $controlPath) {
                preg_match('/\/'.'application'.'\/(.*?)\/Control\/(.*?)Control.php$/', $controlPath, $controlMatches);
                try {
                    $fileInfo = $this->analysisFile($controlPath);
                } catch (\Exception $e) {
//                    fwrite(STDOUT, $e->getMessage() . PHP_EOL);
                    continue;
                }
                $data[] = [
                    'v' => strtolower($controlMatches[1]),
                    'c' => strtolower($controlMatches[2]),
                    'cMenuName' => $fileInfo['cMenuName'],
                    'cGroupName' => $fileInfo['cGroupName'],
                    'actions' => $fileInfo['actions'],
                ];
            }
            if (empty($data)) {
//                fwrite(STDOUT, "{$path}下没有有效的控制器" . PHP_EOL);
                continue;
            }
            $this->insertData($site, $data);
        }
        fwrite(STDOUT, "处理完毕！" . PHP_EOL);
        fwrite(STDOUT, "成功插入：{$this->insertNum} ，成功更新：{$this->updateNum}" . PHP_EOL);
        fwrite(STDOUT, "插入失败：{$this->insertErrorNum},更新失败：{$this->updateErrorNum}" . PHP_EOL);

//        $this->menuOp();
        die;
    }

    /**
     * 获取需要配置的路径
     */
    public function getPath()
    {
        $configPath = Config::get("rpcapipath");
        $paths = [];
        if (is_array($configPath)) {
            foreach ($configPath as $item) {
                $paths[] = glob($item);
            }
        } elseif (!empty($rpcapipath)) {
            $paths[] = glob($rpcapipath);
        }
        return call_user_func_array('array_merge', $paths);
    }

    /**
     * @param $controlPath
     * 解析文件
     */
    private function analysisFile($controlPath)
    {
        $data = [];
        $fileDetail = file_get_contents($controlPath);
        if (empty($fileDetail)) {
            throw new \Exception("{$controlPath}文件为空");
        }
        //获取控制的菜单
        $controlPreg = '/@ControlDescription\s*?\((.*?)\)/i';
        preg_match($controlPreg, $fileDetail, $matches);
        $data['cMenuName'] = $this->getDescription($matches[1], 'menuName');
        $data['cGroupName'] = $this->getDescription($matches[1], 'groupName');
        $data['cGroupName'] = $data['cGroupName'] ? $data['cGroupName'] : $data['cMenuName'];
        //获取所有方法
        $actionPreg = '/@OpDescription\((.*?)\)[\s\S]*?public\s*?function\s*?(\w*?)Op\s*?\(/i';
        $actions = [];
        if(preg_match_all($actionPreg, $fileDetail, $matches)){
            foreach ($matches[0] as $key => $value) {
                $action = $matches[2][$key];
                $whatFor = $this->getDescription($matches[1][$key], 'whatFor');
                $menuName = $this->getDescription($matches[1][$key], 'menuName');
                $codeMonkey = $this->getDescription($matches[1][$key], 'codeMonkey');
                $actions[] = [
                    'action'=>strtolower($action),
                    'whatFor'=>$whatFor,
                    'menuName'=>$menuName,
                    'codeMonkey'=>$codeMonkey,
                ];
            }
        }
        if (empty($actions)) {
            throw new \Exception("{$controlPath}没有有效的方法");
        }
        $data['actions'] = $actions;
        return $data;
    }

    /**
     * @param $str
     * @param $descriptionName
     * @return string
     * 匹配出 对应的 Description的值
     */
    private function getDescription($str, $descriptionName)
    {
        $preg = "/{$descriptionName}\s*?=\s*?(\"|')(.*?)\\1/i";
        $value = '';
        if(preg_match($preg, $str, $matches)){
            $value = trim($matches[2]);
        }
        return $value;
    }

    /**
     * @param $s 站点
     * @param $data 数据
     */
    private function insertData($site, $data)
    {
        $insertApi = [];
        $insertApiMenu = [];
        $insertApiOther = [];
        $serviceSite = $this->serviceSite;
        foreach ($data as $control) {
            foreach ($control['actions'] as $op) {
                $apiCode = md5($site . $control['v'] . $control['c'] . $op['action']);
                $insertApi[$apiCode] = [
                    'apicode'=>$apiCode,
                    'site'=>$site,
                    'c'=>$control['c'],
                    'a'=>$op['action'],
                    'v'=>$control['v'],
                ];
                $siteName = isset($serviceSite[$site]['menuName']) && $serviceSite[$site]['menuName'] ? $serviceSite[$site]['menuName'] : '';

                //是菜单才插入
                if ($op['menuName'] && $control['cMenuName'] && $control['cGroupName'] && $siteName) {
                    $insertApiMenu[$apiCode] = [
                        'apicode'=>$apiCode,
                        'sitename'=>$siteName,
                        'groupname'=>$control['cGroupName'],
                        'cname'=>$control['cMenuName'],
                        'aname'=>$op['menuName'],
                    ];
                }

                $insertApiOther[$apiCode] = [
                    'apicode'=>$apiCode,
                    'what_for'=>$op['whatFor'],
                    'code_monkey'=>$op['codeMonkey'],
                ];
            }
        }
        // api 表处理
        $apiCodes = array_keys($insertApi);
        $model = new Model();
        $result = $model->table('api')->field('apicode')->where(['apicode'=>['in',$apiCodes]])->limit(false)->select();

        //已存在的api，就更新数据
        if ($result) {
            foreach ($result as $apiItem) {
                $menu = isset($insertApiMenu[$apiItem['apicode']]) ? $insertApiMenu[$apiItem['apicode']] : '';
                $this->updateApi($insertApi[$apiItem['apicode']], $menu, $insertApiOther[$apiItem['apicode']]);
                unset($insertApi[$apiItem['apicode']]);
                if (isset($insertApiMenu[$apiItem['apicode']])) {
                    unset($insertApiMenu[$apiItem['apicode']]);
                }
                unset($insertApiOther[$apiItem['apicode']]);
            }
        }
        //插入数据
        if ($insertApi) {
            $insertApi = array_values($insertApi);
            $insertApiMenu = array_values($insertApiMenu);
            $insertApiOther = array_values($insertApiOther);
//            var_dump($insertApi, $insertApiMenu, $insertApiOther);exit;
            $this->insertApi($insertApi, $insertApiMenu, $insertApiOther);
        }
    }

    /**
     * @param $api
     * @param $apiMenu
     * @param $apiOther
     * 更新数据
     */
    private function updateApi($api, $apiMenu, $apiOther)
    {
        $this->apiModel->beginTransaction();
        try{
//            $this->apiModel->where(['apicode'=>$api['apicode']])->update($api);
            if ($apiMenu) {
                $isset = $this->apiMenuModel->where(['apicode'=>$apiMenu['apicode']])->find();
                if ($isset) {
                    $this->apiMenuModel->where(['apicode'=>$apiMenu['apicode']])->update($apiMenu);
                } else {
                    $this->apiMenuModel->insert($apiMenu);
                }
            } else {
                $this->apiMenuModel->where(['apicode'=>$apiOther['apicode']])->delete();
            }
            $this->apiOtherModel->where(['apicode'=>$apiOther['apicode']])->update($apiOther);
            $this->apiModel->commit();
            $this->updateNum++;
        } catch (\Exception $e) {
            $this->apiModel->rollback();
            $this->updateErrorNum++;
            fwrite(STDOUT, "{$api['apicode']}更新失败" . PHP_EOL);
        }
    }

    /**
     * @param $api
     * @param $apiMenu
     * @param $apiOther
     * 插入数据
     */
    private function insertApi($api, $apiMenu, $apiOther)
    {
        $this->apiModel->beginTransaction();
        $step = 100;
        $data['api'] = array_chunk($api, $step);
        $data['apiMenu'] = array_chunk($apiMenu, $step);
        $data['apiOther'] = array_chunk($apiOther, $step);
        try{
            foreach ($data as $key=>$name) {
                array_map(function ($value) use ($key){
                    $this->{$key . 'Model'}->insertAll($value);
                }, $name);
            }
            $this->apiModel->commit();
            $this->insertNum += count($api);
        } catch (\Exception $e) {
            $this->apiModel->rollback();
            $this->insertErrorNum += count($api);
            fwrite(STDOUT, $e->getMessage() . PHP_EOL);
        }
    }
}