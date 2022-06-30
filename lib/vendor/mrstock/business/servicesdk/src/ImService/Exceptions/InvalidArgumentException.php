<?php

namespace GxsIM\Exceptions;

class InvalidArgumentException extends \Exception
{
    public function getErrorInfo()
    {
        return 'Error line ' . $this->getLine().' in ' . $this->getFile() . ': <b>' . $this->getMessage() . '</b> is lacked!!!';
    }

}



















