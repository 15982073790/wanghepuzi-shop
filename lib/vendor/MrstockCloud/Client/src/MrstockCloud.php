<?php

namespace MrstockCloud\Client;

use MrstockCloud\Client\Exception\ClientException;

/**
 * Class MrstockCloud
 *
 * @package   MrstockCloud\Client
 * @mixin     \MrstockCloud\ServiceResolver
 */
class MrstockCloud
{
    /**
     * Version of the Client
     */
    const VERSION = '1.0.15';

    public static $APPCODE = false;
    public static $SECRETKEY;

    /**
     * This static method can directly call the specific service.
     *
     * @param string $serviceName
     * @param array  $arguments
     *
     * @codeCoverageIgnore
     * @return object
     * @throws ClientException
     */
    public static function __callStatic($serviceName, $arguments)
    {
        $serviceName = \ucfirst($serviceName);


        $class = 'MrstockCloud' . '\\' . $serviceName . '\\ModuleResolver' ;

        if(self::$APPCODE===false){
            throw new ClientException(
                'Please register SDK.',
                "MRSTOCK_CLOUD_SDK_NOT_REGISTER"
            );
        }

        if(empty(self::$APPCODE)){
            throw new ClientException(
                'appcode cannot emty.',
                "MRSTOCK_CLOUD_APPCODE_IS_EMPTY"
            );
        }

        if (\class_exists($class)) {
            return new $class;
        }
        else{
            throw new ClientException(
                'Please install MrstockCloud/sdk to support product quick access.',
                "MRSTOCK_CLOUD_SERVICE_NOT_FOUND"
            );
        }
    }

    public static function appcodeSecretKey($appcode,$secretKey)
    {
        self::$APPCODE = $appcode;
        self::$SECRETKEY = $secretKey;
    }
}