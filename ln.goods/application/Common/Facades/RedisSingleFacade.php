<?php

namespace Common\Facades;

use MrStock\System\MJC\Facade;
use MrStock\System\MJC\Container;
use MrStock\System\Redis\RedisHelper;

/**
 * redis操作facade方法 单redis
 */
class RedisSingleFacade extends Facade
{
    protected static function getFacadeClass()
    {
        $isExist = Container::getInstance()->exists("ln");
        if (! $isExist)
            Container::set('ln', new RedisHelper('ln', 0));
        
        return 'ln';
    }
}

