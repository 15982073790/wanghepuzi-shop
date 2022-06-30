<?php

namespace MrstockCloud\Uploadfile\Inneruse\V2\GetToken;

use MrstockCloud\Client\Traits\ApiResolverTrait;

/**
 * Resolve Api based on the method name.
 *
 * @method Getosstoken getosstoken(array $options = []) whatFor="获取oss的token",codeMonkey="申长春" 
 * @method Getheguiosstoken getheguiosstoken(array $options = []) whatFor="获取合规的oss的token",codeMonkey="王云" 
 * @method Getcxtosstoken getcxtosstoken(array $options = []) whatFor="获取财学堂的oss的token",codeMonkey="王云" 
 * @method Getossurl getossurl(array $options = []) whatFor="oss鉴权",codeMonkey="王云" 
 * @method Fileuploader fileuploader(array $options = []) whatFor="图片上传",codeMonkey="王云" 
 * @method Upload upload(array $options = []) whatFor="上传文件",codeMonkey="王云" 
 * @method Fileuploadtooss fileuploadtooss(array $options = []) whatFor="图片上传到oss",codeMonkey="王云" 
 * @method Getimage getimage(array $options = []) whatFor="下载图片",codeMonkey="王云" 

 */
class ApiResolver
{
    use ApiResolverTrait;
}
