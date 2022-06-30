<?php

namespace MrstockCloud\Client\Traits;

use MrstockCloud\Client\Exception\ClientException;

/**
 * Trait VersionResolverTrait
 *
 * @package   MrstockCloud\Client\Traits
 *
 */
trait VersionResolverTrait
{

    /**
     * @var bool
     */
    protected $static = false;

    /**
     * Version Resolver constructor.
     *
     * @param bool $static
     */
    public function __construct($static = false)
    {
        $this->static = $static;
    }

    /**
     * @param      $version
     * @param      $arguments
     *
     * @return mixed
     * @throws ClientException
     */
    public function __call($version, $arguments)
    {
        $serviceName = $this->getServiceName(\get_class($this));
		
		$version = \ucfirst($version);
		$class       = $this->getNamespace(\get_class($this)) . '\\' .$version. "\\ControlResolver";

        if (\class_exists($class)) {
            return new $class();
        }

        throw new ClientException(
            "$class Versions contains no {$version}",
            'SDK.VersionNotFound'
        );
    }

    /**
     * @param $name
     * @param $arguments
     *
     * @return mixed
     */
    public static function __callStatic($name, $arguments)
    {
        return (new static(true))->__call($name, $arguments);
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
        if (isset($array[2])) {
            return $array[2];
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

        if (!isset($array[3])) {
            throw new ClientException(
                'Get namespace error.',
                'SDK.ParseError'
            );
        }

        unset($array[3]);

        return \implode('\\', $array);
    }
}
