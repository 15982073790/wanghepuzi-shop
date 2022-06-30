
<?php

define( 'VENDOR_DIR', '/data/lib/vendor' );
require_once VENDOR_DIR . "/autoload.php";


use MrstockCloud\Client\MrstockCloud;


$url = "http://crm.api.dexunzhenggu.cn/index.php?dx_api_sign=f9kKULpgXN1BLy5MyHlSL31iPjQZ6fue4YUFIJxgeM&c=dxapi&a=checkcode&mobile=49726c2f4b584b2f766b4832415034314847476452413d3d";
$r = \MrStock\System\Helper\HttpRequest::execute($url,$data);
var_dump($r);
exit();

$appcode = '5ca4baf2937f40jyu4z42862';
$secretKey = '';

MrstockCloud::appcodeSecretKey($appcode,$secretKey);


$result =  MrstockCloud::hq()->inneruse()->v()->stockfinance()->getlast(['stockcode'=>'002023','tdsourcetag'=>'s_pcqq_aiomsg'])->request();

var_dump($result);
exit();

$notice2['config_code'] = 'mx10029';

try {

    $data = $result['data'];
    $data['businessdata'] = [
                                'jump_url'=>[],
                                'content'=>[],
                                'recipient'=>[
                                    'member_ids'=>'1,2'
                                ]
                            ];

    $result = MrstockCloud::message()
                          ->inneruse()
                          ->v()->access()->noTice1($data)->request();
var_dump($result);

}
catch ( Exception $ex ) {
	echo $ex->getMessage();
}
