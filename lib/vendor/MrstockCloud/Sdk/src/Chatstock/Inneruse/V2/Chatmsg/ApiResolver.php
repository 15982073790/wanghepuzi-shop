<?php

namespace MrstockCloud\Chatstock\Inneruse\V2\Chatmsg;

use MrstockCloud\Client\Traits\ApiResolverTrait;

/**
 * Resolve Api based on the method name.
 *
 * @method Stocksend stocksend(array $options = [])  
 * @method Stocksubmit stocksubmit(array $options = [])  
 * @method Frendstock frendstock(array $options = [])

 */
class ApiResolver
{
    use ApiResolverTrait;

}
