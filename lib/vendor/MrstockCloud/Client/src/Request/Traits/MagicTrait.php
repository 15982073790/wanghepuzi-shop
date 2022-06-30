<?php

namespace MrstockCloud\Client\Request\Traits;

use MrstockCloud\Client\Request\Request;

/**
 * Class MagicTrait
 *
 * @package   MrstockCloud\Client\Request\Traits
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
