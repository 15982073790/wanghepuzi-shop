<?php

namespace MrstockCloud\Leavingmessage\Inneruse\V2\Comment;

use MrstockCloud\Client\Traits\ApiResolverTrait;

/**
 * Resolve Api based on the method name.
 *
 * @method Add add(array $options = [])  
 * @method Reply reply(array $options = [])  
 * @method Updatestatus updatestatus(array $options = [])  
 * @method Getcomment getcomment(array $options = [])  
 * @method Cancel cancel(array $options = [])  
 * @method Del del(array $options = [])  
 * @method Info info(array $options = [])  
 * @method Updateisread updateisread(array $options = [])  

 */
class ApiResolver
{
    use ApiResolverTrait;
}
