<?php
namespace MrStock\System\MJC\Facade;

use MrStock\System\MJC\Facade;

class Response extends Facade
{
    /**
     * 获取当前Facade对应类名（或者已经绑定的容器对象标识）
     * @access protected
     * @return string
     */
    protected static function getFacadeClass()
    {
        return 'response';
    }
}
