<?php

namespace CxtCloud\Client\Traits;

use CxtCloud\Client\Exception\ClientException;

/**
 * Trait ControlResolverTrait
 *
 * @package   CxtCloud\Client\Traits
 *
 */
trait ControlResolverTrait
{

    /**
     * @var bool
     */
    protected $static = false;

    /**
     * Control Resolver constructor.
     *
     * @param bool $static
     */
    public function __construct($static = false)
    {
        $this->static = $static;
    }

    /**
     * @param      $control
     * @param      $arguments
     *
     * @return mixed
     * @throws ClientException
     */
    public function __call($control, $arguments)
    {
        $serviceName = $this->getServiceName(\get_class($this));
		
		$control = \ucfirst($control);
		$class       = $this->getNamespace(\get_class($this)) . '\\' .$control."\\ApiResolver";

        if (\class_exists($class)) {
            return new $class();
        }

        throw new ClientException(
            "$class Controls contains no {$control}",
            'SDK.ControlNotFound'
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

        if (!isset($array[4])) {
            throw new ClientException(
                'Get namespace error.',
                'SDK.ParseError'
            );
        }

        unset($array[4]);

        return \implode('\\', $array);
    }
}
