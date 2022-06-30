<?php

namespace CxtCloud\Uploadfile\Inneruse\V\Ftpupload;

use MrstockCloud\Client\Traits\ApiResolverTrait;

/**
 * Resolve Api based on the method name.
 *
 * @method Upload_img upload_img(array $options = []) whatFor="上传图片",codeMonkey=""
 * @method Upload upload(array $options = []) whatFor="带有地址的文件上传",codeMonkey=""

 */
class ApiResolver
{
    use ApiResolverTrait;
}
