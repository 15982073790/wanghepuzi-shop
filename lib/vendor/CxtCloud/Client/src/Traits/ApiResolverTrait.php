<?php

namespace CxtCloud\Client\Traits;

use CxtCloud\Client\Exception\ClientException;

/**
 * Trait ApiResolverTrait
 *
 * @package   CxtCloud\Client\Traits
 *
 */
trait ApiResolverTrait
{

    /**
     * @param $name
     * @param $arguments
     *
     * @return mixed
     */
    public static function __callStatic($name, $arguments)
    {
        return (new static())->__call($name, $arguments);
    }

    /**
     * @param $api
     * @param $arguments
     *
     * @return mixed
     * @throws ClientException
     */
    public function __call($api, $arguments)
    {
        $tmpApi = \ucfirst(strtolower($api));
        $class       = $this->getNamespace(\get_class($this)) . '\\' . $tmpApi;

        if (\class_exists($class)) {
            if (isset($arguments[0])) {
                return new $class($arguments[0]);
            }

            return new $class();
        }

        $tmpApi = \ucfirst($api);
        $class       = $this->getNamespace(\get_class($this)) . '\\' . $tmpApi;

        if (\class_exists($class)) {
            if (isset($arguments[0])) {
                return new $class($arguments[0]);
            }

            return new $class();
        }

        throw new ClientException(
            "{$class} contains no $api",
            'SDK.ApiNotFound'
        );
    }

    /**
     * @param string $class
     *
     * @return mixed
     * @throws ClientException
     */
    protected function getServiceName($class)
    {
        $array = \explode('\\', $class);
        if (isset($array[1])) {
            return $array[1];
        }
        throw new ClientException(
            'Service name not found.',
            'SDK.ServiceNotFound'
        );
    }

    /**
     * @param string $class
     *
     * @return string
     * @throws ClientException
     */
    protected function getNamespace($class)
    {
        $array = \explode('\\', $class);

        if (!isset($array[5])) {
            throw new ClientException(
                'Get namespace error.',
                'SDK.ParseError'
            );
        }

        unset($array[5]);

        return \implode('\\', $array);
    }
}
