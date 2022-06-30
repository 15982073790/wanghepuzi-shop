<?php
namespace App\Facade;

use MrStock\System\MJC\Facade;
use MrStock\System\MJC\Container;
use MrStock\System\Redis\RedisHelper;

class HqRedisFacade extends Facade
{

    /**
     * 获取当前Facade对应类名（或者已经绑定的容器对象标识）
     *
     * @access protected
     * @return string
     */
    protected static function getFacadeClass()
    {
        $isExist = Container::getInstance()->exists("hqredis");
        if (! $isExist)
            Container::set('hqredis', new RedisHelper('queue', 8));
        
        return 'hqredis';
    }
}
