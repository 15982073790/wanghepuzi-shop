<?php

$config = array();

$config['route']['module']                  = "AC";
//压缩类型
$config['gip'] 							= 1;
//oss配置
$config['readPolicy'] =<<<POLICY
{
    "Statement": [
        {
            "Action": [
                "oss:DeleteObject",
                "oss:ListParts",
                "oss:AbortMultipartUpload",
                "oss:PutObject",
                "oss:Get*"
            ],
            "Effect": "Allow",
            "Resource": "*"
        }
    ],
    "Version": "1"
}
POLICY;
$config['writePolicy'] =<<<POLICY
{
    "Statement": [
        {
            "Action": [
                "oss:DeleteObject",
                "oss:ListParts",
                "oss:AbortMultipartUpload",
                "oss:PutObject",
                "oss:Get*"
            ],
            "Effect": "Allow",
            "Resource": "*"
        }
    ],
    "Version": "1"
}
POLICY;
$config['huihui_accessKeyId'] = getenv('huihui_accessKeyId');
$config['huihui_accessKeySecret'] =getenv('huihui_accessKeySecret');
$config['huihui_endpoint'] = getenv('huihui_endpoint');
$config['huihui_bucket'] = getenv('huihui_bucket');
$config['huihui_regionId'] = getenv('huihui_regionId');
$config['huihui_readArn'] = getenv('huihui_readArn');
$config['huihui_writeArn'] = getenv('huihui_writeArn');
//oss结束
return $config;