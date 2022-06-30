<?php
/**
 * Created by PhpStorm.
 * User: kevin
 * Date: 2019/10/24
 * Time: 9:39
 */

namespace Init\Control;

use MrStock\System\Helper\Config;
use MrStock\System\MJC\Control;
use MrStock\System\MJC\Http\Request;
use MrStock\System\Orm\Model;


/**
 * 1. 根据接收参数获取需要生成SDK的服务
 * 2. 根据服务名称生成对应SDK
 * 3. 生成SDK push到gitlab
 * 4. 删除clone的服务
 * 5. 删除生成的SDK
 * Class SdkControl
 * @ControlDescription(menuName="SdkControl")
 * @package App\Control
 */
class SdkControl extends Control
{
    private $libpath = ''; // 框架目录
    private $sdkpath = ""; // 生成的SDK目录
    private $projectpath = ""; // clone的服务目录
    private $env = ''; // 当前环境
    private $allenv = ['dev', 'qa', 'pre', 'master']; // 全部环境
    private $changeService = []; // 更改服务
    // private $allservice = []; // 所有服务
    private $fail = 0;
    private $failmsg = [];

    public function __construct(Request $request)
    {
        $this->libpath = Config::get('libpath');
        $this->sdkpath = Config::get("sdkpath");
        $this->projectpath = Config::get("projectpath");
    }

    public function makeOp(Request $request)
    {
//        $changeService = trim($request->param['services']);
        $changeService = $_SERVER['argv'][3];
//        $this->env = trim($request->param['env']); // 当前环境
        if (empty($changeService)) return $this->json('没有需要生成SDK的服务', -1);
//        if (empty($this->env) || !in_array($this->env, $this->allenv)) return $this->json('当前环境错误', -1);
        $this->changeService = explode(',', $changeService);

        $modules = [
            'Inneruse',
        ];

        $versions = [
            'application',
        ];
        $_GET["i"] = 1;
        $_GET["c"] = 1;

        // 删除生成的目录
//        $this->rmdir();

        // clone框架
//        $this->makelib();

        // 生成SDK
        foreach ($versions as $version) {
            $this->start($this->changeService, $this->env, $modules, $version);
        }

        // 推送到仓库
//        $this->push();

        if ($this->fail == 0) {
            // 删除clone服务
            return $this->json('success', 1);
        }

        return $this->json($this->failmsg, -1);
    }

    // 删除目录
    private function rmdir()
    {
        file_put_contents('rm.sh', "rm -rf {$this->libpath}*\n");
        file_put_contents('rm.sh', "rm -rf {$this->projectpath}* \n", FILE_APPEND);
        // system("sh ./rm.sh"); // 脚本路径
        system("sh /data/www/sdk/public/rm.sh"); // 脚本路径
    }

    // clone框架
    private function makelib()
    {
        file_put_contents('lib.sh', "cd {$this->libpath}\n");
        $clone = sprintf(
            'git clone http://%s:%d@gitlab.dexunzhenggu.cn/frame/lib.git %s',
            self::USER,
            self::PASSWD,
            $this->libpath . 'lib'
        );

        file_put_contents('lib.sh', "{$clone}\n", FILE_APPEND);
        file_put_contents('lib.sh', "cd {$this->libpath}lib \n", FILE_APPEND);
        file_put_contents('lib.sh', "git checkout {$this->env} \n", FILE_APPEND);

        // system("sh ./lib.sh"); // 脚本路径
        system("sh /data/www/sdk/public/lib.sh"); // 脚本路径
    }

    private function push()
    {
        /**
         * cd
         * git clone lib
         * git checkout env
         * git add .
         * git push origin dev
         */
        file_put_contents('sdk.sh', "cd {$this->sdkpath}\n");
        file_put_contents('sdk.sh', "git add {$this->sdkpath} \n", FILE_APPEND);
        $email = sprintf(
            'git config user.email %s',
            self::EMAIL
        );
        file_put_contents('sdk.sh', "{$email} \n", FILE_APPEND);
        file_put_contents('sdk.sh', "git commit -m'生成SDK' \n", FILE_APPEND);
        file_put_contents('sdk.sh', "git push origin {$this->env} \n", FILE_APPEND);

        // system('sh ./sdk.sh'); // 脚本路径
        system('sh /data/www/sdk/public/sdk.sh'); // 脚本路径
    }

    /**
     * @param $changeService array  需要生成SDK的service
     * @param $env           string 当前环境
     * @param $result        array  全部服务
     * @param $modules       array  默认 Inneruse
     * @param $version       array  版本
     */
    private function start($changeService, $env, $modules, $version)
    {
        foreach ($changeService as $item) {
            $site = trim($item);
            $servicePath = $this->projectpath . "cxt." . $site . "/";
            $devingPath = $servicePath . $version;
            $rpcapipaths[] = $devingPath;
        }
        $this->startOneService($rpcapipaths, $env, $modules);
    }

    private function startOneService($rpcapipaths, $env, $modules)
    {
        $services = [];
        if (is_array($rpcapipaths)) {
            foreach ($rpcapipaths as $item) {
                foreach ([$item] as $path) {
                    $dirArray = explode('/', $path);
                    if (PHP_OS == 'Linux') {
                        $serviceArray = explode('.', $dirArray[3]);
                    } else {
                        $serviceArray = explode('.', $dirArray[3]);
                    }
                    $path = str_replace('/', "\\", $path);
                    $services[$serviceArray[1]] = $path;
                }
            }
        }
        $this->parseService($services, $modules);
    }

    // 处理服务
    private function parseService($services, $modules)
    {
        $serviceMethods = '';
        foreach ($services as $serviceName => $service) {
            $serviceName = \ucfirst($serviceName);
            $this->handleService($serviceName);

            $this->parseServiceModule($service, $serviceName, $modules);

            $serviceMethods .= " * @method static  " . $serviceName
                . "\ModuleResolver " . strtolower($serviceName)
                . "() $serviceName 服务 \n";
        }
//         if ($serviceMethods) {
//             $this->adapteServiceResolver($serviceMethods);
//         }

        echo "success\n";
    }

    function delDirAndFile($dirName)
    {
        if ($handle = opendir($dirName)) {
            while (false !== ($item = readdir($handle))) {
                if ($item != "." && $item != "..") {
                    if (is_dir($dirName."/".$item)) {
                        $this->delDirAndFile($dirName."/".$item);
                        @rmdir($dirName."/".$item.'/');
                    } else {
                        if (unlink($dirName."/".$item)) echo "成功删除文件： $dirName.\"/\".$item \n";
                    }
                }
            }
            closedir($handle);
            if (rmdir($dirName)) echo "成功删除目录： $dirName \n";
        }
    }

    // 创建服务目录
    private function handleService($serviceName)
    {
        $serviceName = $this->sdkpath . \ucfirst($serviceName);
        $this->delDirAndFile($serviceName);
        $str = "create service dir $serviceName";
        if (is_dir($serviceName)) {
            echo $str . " exist\n";
            return;
        }
        if (mkdir($serviceName, 0777, true)) {
            echo $str . " suc\n";
            return;
        } else {
            echo $str . " fail\n";
            $this->fail++;
            $this->failmsg[] = $str . " fail";
        }
    }

    // 处理模块
    private function parseServiceModule($service, $serviceName, $modules)
    {
        $moduleMethod = '';
        foreach ($modules as $module) {
            $result = $this->parseControlVersion($service, $module, $serviceName);

            if ($result) {
                $moduleMethod .= " * @method " . $module . "\VersionResolver "
                    . strtolower($module) . "() $module 模块 \n";
            }
        }

        if ($moduleMethod) {
            $namespace = $serviceName;
            $this->adapteModuleResolver($namespace, $moduleMethod);
            return true;
        }
        $this->fail++;
        $this->failmsg[] = "创建 $serviceName parseServiceModule 失败";
    }

    // 处理控制器版本
    private function parseControlVersion($service, $moduleName, $serviceName)
    {
        $service = str_replace("\"", "\\", $service);
        $modulePath = $service . "\\" . $moduleName;
        $version = $this->getVersion($modulePath);
        $dirs = [$version];

        if ($dirs && count($dirs) > 0) {
            $this->handleModule($serviceName, $moduleName);
            $methods = '';
            foreach ($dirs as $dir) {
                $versionName = $version;
                $versionDir = "Control\\";

                $this->handleVersion($serviceName, $moduleName, $versionName);
                $versionPath = $modulePath . "\\" . $versionDir;
                $result = $this->parseControl($versionPath, $moduleName, $serviceName, $versionName);
                if ($result) {
                    $methods .= " * @method " . $versionName
                        . "\ControlResolver "
                        . strtolower($versionName)
                        . "() 控制器 $versionName 版本 \n";
                }
            }

            $namespace = $serviceName . "\\" . $moduleName;
            $this->adapteVersionResolver($namespace, $methods);
            return true;
        }
        $this->fail++;
        $this->failmsg[] = "创建 $serviceName parseControlVersion 失败";
    }

    private function getVersion($modulePath)
    {
        $pathArr = explode("\\", $modulePath);
        $version = $pathArr[count($pathArr) - 2];
        if ($version == "application") {
            $version = "V";
        }
        return $version;
    }

    // 创建模块目录
    private function handleModule($serviceName, $moduleName)
    {
        $serviceName = $this->sdkpath . \ucfirst($serviceName) . '/'
            . ucfirst($moduleName);
        $str = "create version dir $serviceName";
        if (is_dir($serviceName)) {
            echo $str . " exist\n";
            return;
        }
        if (mkdir($serviceName)) {
            echo $str . " suc\n";
            return;
        } else {
            echo $str . " fail\n";
            $this->fail++;
            $this->failmsg[] = $str . " fail";
        }
    }

    // 创建控制器版本目录
    private function handleVersion($serviceName, $moduleName, $versionName)
    {
        $serviceName = $this->sdkpath . \ucfirst($serviceName) . '/'
            . ucfirst($moduleName) . '/' . ucfirst($versionName);
        $str = "create version dir $serviceName";
        if (is_dir($serviceName)) {
            echo $str . " exist\n";
            return;
        }
        if (mkdir($serviceName)) {
            echo $str . " suc\n";
            return;
        } else {
            echo $str . " fail\n";
            $this->fail++;
            $this->failmsg[] = $str . " fail";
        }
    }

    // 创建控制器目录
    private function handleControl($serviceName, $moduleName, $versionName, $controlName)
    {
        $serviceName = $this->sdkpath . \ucfirst($serviceName) . '/'
            . ucfirst($moduleName) . '/' . ucfirst($versionName)
            . '/' . $controlName;
        $str = "create control dir $serviceName";
        if (is_dir($serviceName)) {
            echo $str . " exist\n";
            return;
        }
        if (mkdir($serviceName)) {
            echo $str . " suc\n";
            return;
        } else {
            echo $str . " fail\n";
            $this->fail++;
            $this->failmsg[] = $str . " fail";
        }
    }

    // 处理控制器
    private function parseControl($path, $moduleName, $serviceName, $versionName)
    {
        $path = str_replace('\\', '/', $path);
        $scanFiles = scandir($path);
        $methods = "";
        if ($scanFiles && count($scanFiles) > 0) {
            foreach ($scanFiles as $file) {
                $isFile = stripos($file, '.php');
                if ($isFile > 0) {
                    $filePath = $path . DIRECTORY_SEPARATOR . $file;
                    $fileArray = explode('.', $file);
                    $className = $fileArray[0];
                    $contrlName = str_replace('Control', '', $className);
                    $this->handleControl($serviceName, $moduleName, $versionName, $contrlName);

                    $this->parseAction($filePath, $serviceName, $moduleName, $versionName, $contrlName);

                    $methods .= " * @method " . $contrlName . "\ApiResolver "
                        . lcfirst($contrlName) . "() $contrlName 控制器 \n";
                }
            }
            $namespace = $serviceName . "\\" . $moduleName . "\\" . ucfirst($versionName);
            $this->adapteControlResolver($namespace, $methods);
            return true;
        }
        // $this->fail++;
        // $this->failmsg[] = "创建 control $serviceName parseControl 失败";
    }

    // 处理操作
    private function parseAction($filePath, $serviceName, $moduleName, $versionName, $contrlName)
    {
        $content = file_get_contents($filePath);
        $tokens = token_get_all($content);
        $functions = $this->getOpsFromToken($tokens);
        $versionName = ucfirst($versionName);
        $namespace = $serviceName . "\\" . $moduleName . "\\" . $versionName . "\\" . $contrlName;
        $methods = '';
        if ($functions && is_array($functions) && count($functions) > 0) {
            foreach ($functions as $function) {
                $this->adapteApi($namespace, $function['name'], '');

                $comment = $this->parseComment($function['comment']);

                $methods .= " * @method " . $function['name'] . " "
                    . strtolower($function['name']) . "("
                    . 'array $options = []' . ") $comment \n";
            }
            $this->adapteApiResolver($namespace, $methods);
        }
    }

    // 解析php文件
    private function getOpsFromToken($tokens)
    {
        $functions = [];
        $getting_function = false;
        $comment = [];

        foreach ($tokens as $token) {
            if (is_array($token) && $token[0] == T_DOC_COMMENT) {
                $comment = $token[1];
            }

            if (is_array($token) && $token[0] == T_FUNCTION) {
                $getting_function = true;
            }

            if ($getting_function === true) {
                if (is_array($token) && $token[0] == T_STRING) {
                    $function['comment'] = $comment;
                    $function['name'] = $token[1];
                    if (stripos($function['name'], 'Op') > 0) {
                        $function['name'] = \ucfirst(str_replace('Op', '', $function['name']));
                        $functions[] = $function;
                    }
                    $getting_function = false;
                    $comment = [];
                }
            }
        }
        return $functions;
    }

    // private function adapteServiceResolver($methods)
    // {
    //     $templatePath = 'Template/ServiceResolver.php.Templage';
    //     $content = file_get_contents($templatePath);
    //     $content = str_replace("{METHODS}", $methods, $content);
    //     $fileName = $this->sdkpath . "ServiceResolver.php";
    //     file_put_contents($fileName, $content);
    // }

    private function adapteModuleResolver($namespace, $methods)
    {
        $templatePath = 'Template/ModuleResolver.php.Templage';
        $content = file_get_contents($templatePath);
//        $namespace1 = "CxtSdk\\".$namespace;
        $content = str_replace("{NAMESPACE}", $namespace, $content);
        $content = str_replace("{METHODS}", $methods, $content);
        $fileName = $this->sdkpath . $namespace . DIRECTORY_SEPARATOR . "ModuleResolver.php";
        file_put_contents($fileName, $content);
    }

    private function adapteVersionResolver($namespace, $methods)
    {
        if (PHP_OS == 'Linux') {
            $namespace_di = str_replace('\\', '/', $namespace);
        } else {
            $namespace_di = $namespace;
        }
        $fileName = $this->sdkpath . $namespace_di . DIRECTORY_SEPARATOR . "VersionResolver.php";
        if (file_exists($fileName)) {
            $this->appendVersionResolver($namespace, $methods);
        } else {
            $templatePath = 'Template/VersionResolver.php.Templage';
            $content = file_get_contents($templatePath);
//            $namespace1 = "CxtSdk\\".$namespace;
            $content = str_replace("{NAMESPACE}", $namespace, $content);
            $content = str_replace("{METHODS}", $methods, $content);
            $fileName = $this->sdkpath . $namespace_di . DIRECTORY_SEPARATOR . "VersionResolver.php";
            file_put_contents($fileName, $content);
        }
    }


    private function appendVersionResolver($namespace, $methods)
    {
        if (PHP_OS == 'Linux') {
            $namespace_di = str_replace('\\', '/', $namespace);
        } else {
            $namespace_di = $namespace;
        }
        $fileName = $this->sdkpath . $namespace_di . DIRECTORY_SEPARATOR . "VersionResolver.php";
        $content = file_get_contents($fileName);
        // if (stripos($content, $methods) == 0) {
        //     $content = str_replace("*/", $methods . "\n" . "*/", $content);
        // }
        // print_r($content);exit;
        $fileName = $this->sdkpath . $namespace_di . DIRECTORY_SEPARATOR . "VersionResolver.php";
        file_put_contents($fileName, $content);
    }

    private function adapteControlResolver($namespace, $methods)
    {
        $templatePath = 'Template/ControlResolver.php.Templage';
        $content = file_get_contents($templatePath);
//        $namespace1 = "CxtSdk\\".$namespace;
        $content = str_replace("{NAMESPACE}", $namespace, $content);
        $content = str_replace("{METHODS}", $methods, $content);
        if (PHP_OS == 'Linux') {
            $namespace = str_replace('\\', '/', $namespace);
        }
        $fileName = $this->sdkpath . $namespace . DIRECTORY_SEPARATOR . "ControlResolver.php";
        file_put_contents($fileName, $content);
    }

    private function adapteApiResolver($namespace, $methods)
    {
        $templatePath = 'Template/ApiResolver.php.Templage';
        $content = file_get_contents($templatePath);
//        $namespace1 = "CxtSdk\\".$namespace;
        $content = str_replace("{NAMESPACE}", $namespace, $content);
        $content = str_replace("{METHODS}", $methods, $content);
        if (PHP_OS == 'Linux') {
            $namespace = str_replace('\\', '/', $namespace);
        }
        $fileName = $this->sdkpath . $namespace . DIRECTORY_SEPARATOR . "ApiResolver.php";
        file_put_contents($fileName, $content);
    }

    private function adapteApi($namespace, $className, $ops)
    {
        $_GET["i"] = intval($_GET["i"]) + 1;
        $templatePath = 'Template/Api.php.Templage';
        $content = file_get_contents($templatePath);
//        $namespace1 = "CxtSdk\\".$namespace;
        $content = str_replace("{NAMESPACE}", $namespace, $content);
        $content = str_replace("{CLASSNAME}", $className, $content);
        $content = str_replace("{OPS}", $ops, $content);
        if (PHP_OS == 'Linux') {
            $namespace = str_replace('\\', '/', $namespace);
        }
        $fileName = $this->sdkpath . $namespace . DIRECTORY_SEPARATOR . $className . ".php";
        if ($this->checkCase($className) > 1) {
            $_GET["c"] = intval($_GET["c"]) + 1;
            $_GET["caceU"][] = $fileName;
            echo $className;
            echo "\n";
        }
        file_put_contents($fileName, $content);
    }


    // 解析注释
    private function parseComment($detail)
    {
        $actionPreg = '/@OpDescription\((.*?)\)/';
        $str = '';
        if (preg_match_all($actionPreg, $detail, $matches)) {
            $str = $matches[1][0];
            return $str;
        }

        return $str;
    }

    private function checkCase($str)
    {
        $U = 0;
        $C = 0;
        for ($i = 0; $i < strlen($str); $i++) {
            $s = substr($str, $i, 1);
            if (preg_match('/^[A-Z]+$/', $s)) {
                $U++;
            } elseif (preg_match('/^[a-z]+$/', $s)) {
                $C++;
            }
        }
        return $U;
    }

}
