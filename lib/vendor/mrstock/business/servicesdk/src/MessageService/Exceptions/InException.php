<?php
namespace GxsMail\Exceptions;

/**
* 异常报错处理
*/
class InException extends \Exception
{
	public function getErrorInfo()
    {
        return 'Error line ' . $this->getLine().' in ' . $this->getFile() . ': <b>' . $this->getMessage() . '</b> is lacked!!!';
    }
}