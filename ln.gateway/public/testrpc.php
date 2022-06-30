<?php


// 是否开启调试模式
define("APP_DEBUG", true);

// 是否命令行
define('IS_CLI', 1);

// 是否接口模式
define('IS_API', 0);

require_once 'app.php';

$r= ["c"=>"Appcodeapi","a"=>"checkappcodeapi","app_code"=>"5b6ab3bcd621ds8k51ucz4uo","url"=>"secretmobile.guxiansheng.cn","method"=>"get"];
$r= ["c"=>"Server","a"=>"checkServersToken","servicestoken"=>"5b6ab3bcd621ds8k51ucz4uoYTcyVFRHeFgxaWZ6cXpkMzlBR1B0dz09"];

$r= ["c"=>"Api","a"=>"add"];


$_REQUEST = $_POST = $_GET=$r;
try
{
       
        $app=new MrStock\System\MJC\App();
        
        $rs=$app->run();
        
        $data = $rs->getContent();
}
catch(Exception $ex){
	var_dump($ex->getMessage());
       // $data = MrStock\System\Helper\Api::resultFormat($ex->getMessage(),-500);
        //$data = json_encode($data);
}
echo $data;
?>