<?php
namespace MrStock\System\MJC;

use MrStock\System\Helper\Output;

class Control
{

    public $app;

    public $middleware;

    public function json($data, $code = 1)
    {
    
        $response = Output::response($data, $code);
        
        return $response;
    }
}