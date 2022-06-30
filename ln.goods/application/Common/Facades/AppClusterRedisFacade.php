<?php

namespace Common\Facades;

use MrStock\System\MJC\Facade;
use MrStock\System\MJC\Container;
use MrStock\System\Redis\RedisHelper;

/**
 * redis操作facade方法
 */
class AppClusterRedisFacade extends Facade
{
    protected static function getFacadeClass()
    {
        $isExist = Container::getInstance()->exists("app_old_redis");
        if (! $isExist)
            Container::set('app_old_redis', new RedisHelper('cxt_old', 0));
        
        return 'app_old_redis';
    }
}
