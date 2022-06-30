<?php
namespace MrStock\System\MJC\Exception;

use MrStock\System\MJC\Http\Response;

class HttpResponseException extends \RuntimeException
{
    /**
     * @var Response
     */
    protected $response;

    public function __construct(Response $response)
    {
        $this->response = $response;
    }

    public function getResponse()
    {
        return $this->response;
    }
}
