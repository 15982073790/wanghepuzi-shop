<?php

namespace CxtCloud\Bean\Inneruse\V\MemberBean;

use MrstockCloud\Client\Traits\ApiResolverTrait;

/**
 * Resolve Api based on the method name.
 *
 * @method Getbeanbyid getbeanbyid(array $options = [])  
 * @method Getbeanbyids getbeanbyids(array $options = [])  
 * @method Freeze freeze(array $options = [])  
 * @method Unfreeze unfreeze(array $options = [])  
 * @method Deductfreeze deductfreeze(array $options = [])  

 */
class ApiResolver
{
    use ApiResolverTrait;
}
