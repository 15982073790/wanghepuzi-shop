<?php
/**
 * Created by PhpStorm.
 * User: luar
 * Date: 2019/3/27
 * Time: 14:32
 */

require_once "config.php";

\MrStock\System\Helper\Config::register();
$model = new \MrStock\System\Orm\Model('','gateway');

$result = $model->table('api')->group('site')->field('site')->select();

$modules   = [
    'Inneruse',
];

$versions = [
    'application',
    'V2'
];
$_GET["i"] = 1;
$_GET["c"] = 1;



foreach ($versions as $version) {
    start($result, $modules, $version);
}


function start($result, $modules,$version)
{
	$rpcapipaths = [];
    foreach ($result as $item) {
        
        $site        = trim($item['site']);
        $servicePath = $GLOBALS['projectpath'] . $site . ".service/";
        $devingPath = $servicePath . "branches/deving/".$version;

        //如果项目文件夹不存在 从svn  checkout
        if ( ! is_dir($servicePath)) {
            mkdir($servicePath);
            putenv('LANG=C.UTF-8');
            $svnCmd = 'svn checkout https://192.168.0.223:8443/svn/' . $site
                      . '.service/ ' . $servicePath
                      . '/ '.$GLOBALS['svnaccount'].' 2>&1';
            $result = shell_exec($svnCmd);

            echo $result;
            echo "\n";
        }

            $rpcapipaths[] = $devingPath;

    }

        startOneService($rpcapipaths, $modules);
}

//解析服务
function startOneService($rpcapipaths, $modules)
{
    $services = [];
    if (is_array($rpcapipaths)) {
        foreach ($rpcapipaths as $item) {

            $paths = [];
            $paths = glob($item);

            foreach ($paths as $path) {

                print_r($path);echo "\n";
                putenv('LANG=C.UTF-8');
                $result = shell_exec('svn update --accept theirs-full '.$path.' 2>&1');

                "result:".print_r($result);echo "\n";

                $dirArray     = explode('/', $path);
                $serviceArray = explode('.', $dirArray[3]);

                $path                       = str_replace('/', "\\", $path);
                $services[$serviceArray[0]] = $path;
            }


        }
    }
    parseService($services, $modules);
}

//创建服务目录
function handleService($serviceName)
{
    $serviceName = $GLOBALS['sdkpath'] . \ucfirst($serviceName);
    $str         = "create service dir $serviceName";
    if (is_dir($serviceName)) {
        echo $str . " exsit\n";

        return;
    }
    if (mkdir($serviceName)) {
        echo $str . " suc\n";
    } else {
        echo $str . " fail\n";
        exit();
    }
}

//创建模块目录
function handleModule($serviceName, $moduleName)
{
    $serviceName = $GLOBALS['sdkpath'] . \ucfirst($serviceName) . '/'
                   . ucfirst($moduleName);
    $str         = "create version dir $serviceName";
    if (is_dir($serviceName)) {
        echo $str . " exsit\n";

        return;
    }
    if (mkdir($serviceName)) {
        echo $str . " suc\n";
    } else {
        echo $str . " fail\n";
        exit();
    }
}

//创建控制器版本目录
function handleVersion($serviceName, $moduleName, $versionName)
{
    $serviceName = $GLOBALS['sdkpath'] . \ucfirst($serviceName) . '/'
                   . ucfirst($moduleName) . '/' . ucfirst($versionName);
    $str         = "create version dir $serviceName";
    if (is_dir($serviceName)) {
        echo $str . " exsit\n";

        return;
    }
    if (mkdir($serviceName)) {
        echo $str . " suc\n";
    } else {
        echo $str . " fail\n";
        exit();
    }
}

//创建控制器目录
function handleControl(
    $serviceName,
    $moduleName,
    $versionName,
    $controlName
) {
    $serviceName = $GLOBALS['sdkpath'] . \ucfirst($serviceName) . '/'
                   . ucfirst($moduleName) . '/' . ucfirst($versionName)
                   . '/' . $controlName;
    $str         = "create control dir $serviceName";
    if (is_dir($serviceName)) {
        echo $str . " exsit\n";

        return;
    }
    if (mkdir($serviceName)) {
        echo $str . " suc\n";
    } else {
        echo $str . " fail\n";
        exit();
    }
}

//处理服务
function parseService($services, $modules)
{
    $serviceNameArray = [];
    $serviceMethods   = '';
    foreach ($services as $serviceName => $service) {

        $serviceName = \ucfirst($serviceName);
        handleService($serviceName);

        $result = parseServiceModule($service, $serviceName, $modules);

        //if ($result) {
            $serviceMethods .= " * @method static  " . $serviceName
                               . "\ModuleResolver " . strtolower($serviceName)
                               . "() $serviceName 服务 \n";
        //}
    }
    if ($serviceMethods) {
        adapteServiceResolver($serviceMethods);
    }

    echo "suc";
}

//处理模块
function parseServiceModule($service, $serviceName, $modules)
{
    $moduleMethod = '';
    foreach ($modules as $module) {

        $result = parseControlVersion($service, $module, $serviceName);

        if ($result) {
            $moduleMethod .= " * @method " . $module . "\VersionResolver "
                             . strtolower($module) . "() $module 模块 \n";
        }
    }

    if ($moduleMethod) {
        $namespace = $serviceName;
        adapteModuleResolver($namespace, $moduleMethod);

        return true;
    }

    return false;
}

function getVersion($modulePath){
    $pathArr = explode("\\",$modulePath);
    $version =  $pathArr[count($pathArr)-2];
    if($version=="application"){
        $version = "V";
    }
    return $version;
}

//处理控制器版本
function parseControlVersion($service, $moduleName, $serviceName)
{
    $service    = str_replace("\"", "\\", $service);
    $modulePath = $service . "\\" . $moduleName;

    //获取控制器版本文件夹
    //$dirs = scandir($modulePath);

    $version = getVersion($modulePath);
    $dirs = [$version];

    if ($dirs && count($dirs) > 0) {
        handleModule($serviceName, $moduleName);
        $methods = '';
        foreach ($dirs as $dir) {


                $versionName = $version;
                $versionDir  = "Control";


                handleVersion($serviceName, $moduleName, $versionName);
                $versionPath = $modulePath . "\\" . $versionDir;
                $result      = parseControl($versionPath, $moduleName,
                    $serviceName,
                    $versionName);
                if ($result) {
                    $methods .= " * @method " . $versionName
                                . "\ControlResolver "
                                . strtolower($versionName)
                                . "() 控制器 $versionName 版本 \n";
                }

        }

        $namespace = $serviceName . "\\" . $moduleName;
        adapteVersionResolver($namespace, $methods);

        return true;
    }

    return false;
}

//处理控制器
function parseControl($path, $moduleName, $serviceName, $versionName)
{
    $scanFiles = scandir($path);
    $files     = [];
    $methods   = "";
    if ($scanFiles && count($scanFiles) > 0) {
        foreach ($scanFiles as $file) {
            $isFile = stripos($file, '.php');
            if ($isFile > 0) {
                $filePath   = $path . "\\" . $file;
                $fileArray  = explode('.', $file);
                $className  = $fileArray[0];
                $contrlName = str_replace('Control', '', $className);
                handleControl($serviceName, $moduleName, $versionName,
                    $contrlName);

                parseAction($filePath, $serviceName, $moduleName,
                    $versionName, $contrlName);

                $methods .= " * @method " . $contrlName . "\ApiResolver "
                            . lcfirst($contrlName) . "() $contrlName 控制器 \n";
            }
        }
        $namespace = $serviceName . "\\" . $moduleName . "\\" . $versionName;
        adapteControlResolver($namespace, $methods);

        return true;
    }

    return false;
}

//处理操作
function parseAction(
    $filePath,
    $serviceName,
    $moduleName,
    $versionName,
    $contrlName
) {

    $content   = file_get_contents($filePath);
    $tokens    = token_get_all($content);
    $functions = getOpsFromToken($tokens);

    $namespace = $serviceName . "\\" . $moduleName . "\\" . $versionName . "\\"
                 . $contrlName;
    $methods   = '';
    if ($functions && is_array($functions) && count($functions) > 0) {
        foreach ($functions as $function) {
            adapteApi($namespace, $function['name'], '');


            $comment = parseComment($function['comment']);

            $methods .= " * @method " . $function['name'] . " "
                        . strtolower($function['name']) . "("
                        . 'array $options = []' . ") $comment \n";
        }
        adapteApiResolver($namespace, $methods);
    }
}

//解析注释
function parseComment($detail)
{
    $actionPreg = '/@OpDescription\((.*?)\)/';
    $str        = '';
    if (preg_match_all($actionPreg, $detail, $matches)) {
        $str = $matches[1][0];

        return $str;
    }

    return $str;
}

function adapteApi($namespace, $className, $ops)
{
    $_GET["i"]    = intval($_GET["i"]) + 1;
    $templatePath = 'Template/Api.php.Templage';
    $content      = file_get_contents($templatePath);
    $content      = str_replace("{NAMESPACE}", $namespace, $content);
    $content      = str_replace("{CLASSNAME}", $className, $content);
    $content      = str_replace("{OPS}", $ops, $content);
    $fileName     = $GLOBALS['sdkpath'] . $namespace . "\\" . $className
                    . ".php";
    if(checkCase($className)>1)
    {
        $_GET["c"]    = intval($_GET["c"]) + 1;
        $_GET["caceU"][] = $fileName;
        echo $className;echo "\n";
    }
    file_put_contents($fileName, $content);
}

function adapteApiResolver($namespace, $methods)
{
    $templatePath = 'Template/ApiResolver.php.Templage';
    $content      = file_get_contents($templatePath);
    $content      = str_replace("{NAMESPACE}", $namespace, $content);
    $content      = str_replace("{METHODS}", $methods, $content);
    $fileName     = $GLOBALS['sdkpath'] . $namespace . "\\ApiResolver.php";
    file_put_contents($fileName, $content);
}


function adapteControlResolver($namespace, $methods)
{
    $templatePath = 'Template/ControlResolver.php.Templage';
    $content      = file_get_contents($templatePath);
    $content      = str_replace("{NAMESPACE}", $namespace, $content);
    $content      = str_replace("{METHODS}", $methods, $content);
    $fileName     = $GLOBALS['sdkpath'] . $namespace . "\\ControlResolver.php";
    file_put_contents($fileName, $content);
}

function adapteVersionResolver($namespace, $methods)
{
    $fileName     = $GLOBALS['sdkpath'] . $namespace . "\\VersionResolver.php";
    if(file_exists($fileName)) {
        appendVersionResolver($namespace, $methods);
    }
    else{
        $templatePath = 'Template/VersionResolver.php.Templage';
        $content = file_get_contents($templatePath);
        $content = str_replace("{NAMESPACE}", $namespace, $content);
        $content = str_replace("{METHODS}", $methods, $content);
        $fileName = $GLOBALS['sdkpath'] . $namespace . "\\VersionResolver.php";
        file_put_contents($fileName, $content);
    }
}

function appendVersionResolver($namespace, $methods)
{
    $fileName     = $GLOBALS['sdkpath'] . $namespace . "\\VersionResolver.php";
    $content      = file_get_contents($fileName);
    if(stripos($content,$methods)==0) {
        $content = str_replace("*/", $methods . "\n" . "*/", $content);
    }
    $fileName     = $GLOBALS['sdkpath'] . $namespace . "\\VersionResolver.php";
    file_put_contents($fileName, $content);
}

function adapteModuleResolver($namespace, $methods)
{
    $templatePath = 'Template/ModuleResolver.php.Templage';
    $content      = file_get_contents($templatePath);
    $content      = str_replace("{NAMESPACE}", $namespace, $content);
    $content      = str_replace("{METHODS}", $methods, $content);
    $fileName     = $GLOBALS['sdkpath'] . $namespace . "\\ModuleResolver.php";
    file_put_contents($fileName, $content);
}

function adapteServiceResolver($methods)
{
    $templatePath = 'Template/ServiceResolver.php.Templage';
    $content      = file_get_contents($templatePath);
    $content      = str_replace("{METHODS}", $methods, $content);
    $fileName     = $GLOBALS['sdkpath'] . "ServiceResolver.php";
    file_put_contents($fileName, $content);
}

//解析php文件
function getOpsFromToken($tokens)
{
    $functions        = [];
    $getting_function = false;
    $comment          = [];

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
                $function['name']    = $token[1];
                if (stripos($function['name'], 'Op') > 0) {
                    $function['name'] = \ucfirst(str_replace('Op', '',
                        $function['name']));
                    $functions[]      = $function;
                }
                $getting_function = false;
                $comment          = [];
            }
        }
    }

    return $functions;
}

function checkCase($str){
    $U = 0;
    $C = 0;
    for($i=0;$i<strlen($str);$i++){
        $s=substr($str,$i,1);
        if(preg_match('/^[A-Z]+$/', $s)){
            $U++;
        }elseif(preg_match('/^[a-z]+$/', $s)){
            $C++;
        }
    }
    return $U;
}


echo "\n";
echo $_GET["i"];
echo "\n";
echo $_GET["c"];
var_dump($_GET["caceU"]);