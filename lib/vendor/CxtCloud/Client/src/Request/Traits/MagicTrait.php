<?php

namespace CxtCloud\Client\Request\Traits;

use CxtCloud\Client\Request\Request;

/**
 * Class MagicTrait
 *
 * @package   CxtCloud\Client\Request\Traits
 *
 * @mixin Request
 */
trait MagicTrait
{
    /**
     * @param string $methodName
     * @param int    $start
     *
     * @return string
     */
    protected function propertyNameByMethodName($methodName, $start = 3)
    {
        return \mb_strcut($methodName, $start);
    }
}
