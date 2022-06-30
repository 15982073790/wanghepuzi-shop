<?php

namespace MrstockCloud\User\Inneruse\V2\Test;

use MrstockCloud\Client\Traits\ApiResolverTrait;

/**
 * Resolve Api based on the method name.
 *
 * @method Mobile mobile(array $options = [])  
 * @method Rpc rpc(array $options = [])  
 * @method Redis redis(array $options = [])  
 * @method Get_mobile_info get_mobile_info(array $options = [])  
 * @method Get_member_info get_member_info(array $options = [])  
 * @method Sms sms(array $options = [])  
 * @method Tasksafterregistration tasksafterregistration(array $options = [])  

 */
class ApiResolver
{
    use ApiResolverTrait;
}
