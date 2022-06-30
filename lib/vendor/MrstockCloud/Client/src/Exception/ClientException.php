<?php

namespace MrstockCloud\Client\Exception;

/**
 * Class ClientException
 *
 * @package   MrstockCloud\Client\Exception
 */
class ClientException extends MrstockCloudException
{
    protected $MRSTOCK_CLOUD_SERVICE_NOT_FOUND = -901;
    protected $MRSTOCK_CLOUD_MODULE_NOT_FOUND = -902;
    protected $MRSTOCK_CLOUD_VERSION_NOT_FOUND = -903;
    protected $MRSTOCK_CLOUD_CONTROL_NOT_FOUND = -904;
    protected $MRSTOCK_CLOUD_OP_NOT_FOUND = -905;
    protected $MRSTOCK_CLOUD_APPCODE_IS_EMPTY = -906;
    protected $MRSTOCK_CLOUD_SDK_NOT_REGISTER = -907;

    /**
     * ClientException constructor.
     *
     * @param string          $errorMessage
     * @param string          $errorCode
     * @param \Exception|null $previous
     */
    public function __construct($errorMessage, $errorCode, $previous = null)
    {
        parent::__construct($errorCode.' '.$errorMessage, $this->$errorCode , $previous);
        $this->errorMessage = $errorMessage;
        $this->errorCode    = $errorCode;
    }

    /**
     * @codeCoverageIgnore
     *
     * @deprecated deprecated since version 2.0.
     *
     * @return string
     */
    public function getErrorType()
    {
        return 'Client';
    }
}
