<?php

namespace MrstockCloud\Love\Inneruse\V2\Favorites;

use MrstockCloud\Client\Traits\ApiResolverTrait;

/**
 * Resolve Api based on the method name.
 *
 * @method Getfavoriteslist getfavoriteslist(array $options = []) whatFor="获取收藏列表",menuName="",codeMonkey="ty" 
 * @method Clickfavorites clickfavorites(array $options = []) whatFor="收藏数量",menuName="",codeMonkey="" 

 */
class ApiResolver
{
    use ApiResolverTrait;
}
